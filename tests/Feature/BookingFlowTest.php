<?php

namespace Tests\Feature;

use App\Enums\StatusBooking;
use App\Models\Booking;
use App\Models\Homestay;
use App\Models\Kamar;
use App\Models\PaketWisata;
use App\Models\Pembayaran;
use App\Models\Ulasan;
use App\Models\User;
use App\Models\Wisatawan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    private function wisatawanUser(): array
    {
        $user = User::factory()->wisatawan()->create();
        $pasangan = Wisatawan::factory()->create(['id_user' => $user->id_user]);

        return [$user, $pasangan];
    }

    private function room(int $capacity = 3, float $price = 200000): Kamar
    {
        $homestay = Homestay::factory()->create();

        return Kamar::factory()->create([
            'id_homestay' => $homestay->id_homestay,
            'kapasitas' => $capacity,
            'harga_per_malam' => $price,
        ]);
    }

    private function payload(Kamar $kamar, array $overrides = []): array
    {
        return array_merge([
            'id_kamar' => $kamar->id_kamar,
            'tgl_checkin' => now()->addDays(10)->toDateString(),
            'tgl_checkout' => now()->addDays(12)->toDateString(),
            'jumlah_tamu' => 2,
        ], $overrides);
    }

    public function test_booking_calculates_total_from_room_and_packages(): void
    {
        [$user, $wisatawan] = $this->wisatawanUser();
        $kamar = $this->room(3, 200000);

        $package = PaketWisata::factory()->create([
            'id_homestay' => $kamar->id_homestay,
            'harga' => 75000,
        ]);

        $response = $this->actingAs($user)->post(route('wisatawan.booking.store'), $this->payload($kamar, [
            'paket' => [$package->id_paket => 2],
        ]));

        $booking = Booking::firstOrFail();

        $response->assertRedirect(route('wisatawan.booking.show', $booking));
        $this->assertSame('menunggu_pembayaran', $booking->status->value);
        $this->assertSame('550000.00', $booking->total_harga);
        $this->assertSame(2, $booking->jumlah_malam);
        $this->assertDatabaseHas('booking_paket', [
            'id_booking' => $booking->id_booking,
            'id_paket' => $package->id_paket,
            'jumlah_peserta' => 2,
            'subtotal' => 150000,
        ]);
    }

    public function test_total_price_is_snapshotted_when_room_price_changes(): void
    {
        [$user] = $this->wisatawanUser();
        $kamar = $this->room(2, 300000);

        $this->actingAs($user)->post(route('wisatawan.booking.store'), $this->payload($kamar));

        $booking = Booking::firstOrFail();
        $kamar->update(['harga_per_malam' => 900000]);

        $this->assertSame('600000.00', $booking->fresh()->total_harga);
    }

    public function test_booking_rejects_overlapping_dates(): void
    {
        [$user, $wisatawan] = $this->wisatawanUser();
        $kamar = $this->room();

        Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
            'tgl_checkin' => now()->addDays(10)->toDateString(),
            'tgl_checkout' => now()->addDays(12)->toDateString(),
            'status' => StatusBooking::Dikonfirmasi->value,
        ]);

        $response = $this->actingAs($user)->post(route('wisatawan.booking.store'), $this->payload($kamar, [
            'tgl_checkin' => now()->addDays(11)->toDateString(),
            'tgl_checkout' => now()->addDays(13)->toDateString(),
        ]));

        $response->assertSessionHasErrors('tgl_checkin');
        $this->assertSame(1, Booking::count());
    }

    public function test_booking_allows_dates_that_touch_an_existing_stay(): void
    {
        [$user, $wisatawan] = $this->wisatawanUser();
        $kamar = $this->room();

        Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
            'tgl_checkin' => now()->addDays(10)->toDateString(),
            'tgl_checkout' => now()->addDays(12)->toDateString(),
            'status' => StatusBooking::Dikonfirmasi->value,
        ]);

        $this->actingAs($user)
            ->post(route('wisatawan.booking.store'), $this->payload($kamar, [
                'tgl_checkin' => now()->addDays(12)->toDateString(),
                'tgl_checkout' => now()->addDays(14)->toDateString(),
            ]))
            ->assertSessionHasNoErrors();

        $this->assertSame(2, Booking::count());
    }

    public function test_cancelled_booking_does_not_block_the_room(): void
    {
        [$user, $wisatawan] = $this->wisatawanUser();
        $kamar = $this->room();

        Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
            'tgl_checkin' => now()->addDays(10)->toDateString(),
            'tgl_checkout' => now()->addDays(12)->toDateString(),
            'status' => StatusBooking::Dibatalkan->value,
        ]);

        $this->actingAs($user)
            ->post(route('wisatawan.booking.store'), $this->payload($kamar))
            ->assertSessionHasNoErrors();

        $this->assertSame(2, Booking::count());
    }

    public function test_booking_rejects_more_guests_than_capacity(): void
    {
        [$user] = $this->wisatawanUser();
        $kamar = $this->room(2);

        $this->actingAs($user)
            ->post(route('wisatawan.booking.store'), $this->payload($kamar, ['jumlah_tamu' => 3]))
            ->assertSessionHasErrors('jumlah_tamu');

        $this->assertSame(0, Booking::count());
    }

    public function test_other_tourist_cannot_view_or_cancel_a_booking(): void
    {
        [, $pemilikBooking] = $this->wisatawanUser();
        [$lain] = $this->wisatawanUser();
        $kamar = $this->room();

        $booking = Booking::factory()->create([
            'id_wisatawan' => $pemilikBooking->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
        ]);

        $this->actingAs($lain)->get(route('wisatawan.booking.show', $booking))->assertForbidden();
        $this->actingAs($lain)->patch(route('wisatawan.booking.cancel', $booking))->assertForbidden();
    }

    public function test_owner_cannot_open_tourist_booking_pages(): void
    {
        $owner = User::factory()->pemilik()->create();
        \App\Models\PemilikHomestay::factory()->create(['id_user' => $owner->id_user]);
        $kamar = $this->room();

        $this->actingAs($owner)->get(route('wisatawan.booking.create', $kamar))->assertForbidden();
        $this->actingAs($owner)->get(route('wisatawan.booking.index'))->assertForbidden();
    }

    public function test_tourist_can_cancel_a_pending_booking(): void
    {
        [$user, $wisatawan] = $this->wisatawanUser();
        $kamar = $this->room();

        $booking = Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
            'status' => StatusBooking::MenungguPembayaran->value,
        ]);

        $this->actingAs($user)
            ->patch(route('wisatawan.booking.cancel', $booking))
            ->assertRedirect(route('wisatawan.booking.show', $booking));

        $this->assertSame('dibatalkan', $booking->fresh()->status->value);
    }

    public function test_tourist_cannot_cancel_confirmed_booking(): void
    {
        [$user, $wisatawan] = $this->wisatawanUser();
        $kamar = $this->room();

        $booking = Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
            'status' => StatusBooking::Dikonfirmasi->value,
        ]);

        $this->actingAs($user)
            ->patch(route('wisatawan.booking.cancel', $booking))
            ->assertForbidden();

        $this->assertSame('dikonfirmasi', $booking->fresh()->status->value);
    }

    public function test_payment_upload_stores_private_proof_and_moves_booking_to_verification(): void
    {
        Storage::fake('local');

        [$user, $wisatawan] = $this->wisatawanUser();
        $kamar = $this->room();
        $booking = Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
            'status' => StatusBooking::MenungguPembayaran->value,
            'total_harga' => 400000,
        ]);

        $response = $this->actingAs($user)->post(route('wisatawan.booking.pembayaran', $booking), [
            'metode' => 'Transfer Bank',
            'bukti_transfer' => UploadedFile::fake()->create('bukti.pdf', 120, 'application/pdf'),
        ]);

        $response->assertRedirect(route('wisatawan.booking.show', $booking));

        $payment = Pembayaran::firstOrFail();
        $this->assertSame('menunggu', $payment->status_verifikasi->value);
        $this->assertSame('400000.00', $payment->jumlah);
        $this->assertTrue(Storage::disk('local')->exists($payment->bukti_transfer));
        $this->assertSame('menunggu_verifikasi', $booking->fresh()->status->value);
    }

    public function test_payment_proof_validation_rejects_wrong_type_and_oversized_file(): void
    {
        Storage::fake('local');

        [$user, $wisatawan] = $this->wisatawanUser();
        $kamar = $this->room();
        $booking = Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
        ]);

        $this->actingAs($user)
            ->post(route('wisatawan.booking.pembayaran', $booking), [
                'metode' => 'QRIS',
                'bukti_transfer' => UploadedFile::fake()->create('bukti.exe', 50, 'application/x-msdownload'),
            ])
            ->assertSessionHasErrors('bukti_transfer');

        $this->actingAs($user)
            ->post(route('wisatawan.booking.pembayaran', $booking), [
                'metode' => 'QRIS',
                'bukti_transfer' => UploadedFile::fake()->create('besar.pdf', 3000, 'application/pdf'),
            ])
            ->assertSessionHasErrors('bukti_transfer');

        $this->assertSame(0, Pembayaran::count());
    }

    public function test_other_tourist_cannot_upload_payment_for_someone_else(): void
    {
        Storage::fake('local');

        [, $pemilikBooking] = $this->wisatawanUser();
        [$lain] = $this->wisatawanUser();
        $kamar = $this->room();

        $booking = Booking::factory()->create([
            'id_wisatawan' => $pemilikBooking->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
        ]);

        $this->actingAs($lain)
            ->post(route('wisatawan.booking.pembayaran', $booking), [
                'metode' => 'Transfer Bank',
                'bukti_transfer' => UploadedFile::fake()->create('bukti.pdf', 100, 'application/pdf'),
            ])
            ->assertForbidden();

        $this->assertSame(0, Pembayaran::count());
    }

    public function test_review_is_only_allowed_for_completed_booking_and_only_once(): void
    {
        [$user, $wisatawan] = $this->wisatawanUser();
        $kamar = $this->room();

        $confirmed = Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
            'status' => StatusBooking::Dikonfirmasi->value,
        ]);

        $this->actingAs($user)
            ->post(route('wisatawan.ulasan.store', $confirmed), [
                'rating' => 5,
                'komentar' => 'Pengalaman yang menyenangkan sekali.',
            ])
            ->assertForbidden();

        $completed = Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
            'tgl_checkin' => now()->subDays(3)->toDateString(),
            'tgl_checkout' => now()->subDay()->toDateString(),
            'status' => StatusBooking::Selesai->value,
        ]);

        $this->actingAs($user)
            ->post(route('wisatawan.ulasan.store', $completed), [
                'rating' => 5,
                'komentar' => 'Tuan rumah sangat ramah dan sarapannya lezat.',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(1, Ulasan::where('id_booking', $completed->id_booking)->count());

        $this->actingAs($user)
            ->get(route('wisatawan.ulasan.create', $completed))
            ->assertForbidden();
    }

    public function test_review_validation_requires_rating_and_comment(): void
    {
        [$user, $wisatawan] = $this->wisatawanUser();
        $kamar = $this->room();

        $completed = Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
            'tgl_checkin' => now()->subDays(3)->toDateString(),
            'tgl_checkout' => now()->subDay()->toDateString(),
            'status' => StatusBooking::Selesai->value,
        ]);

        $this->actingAs($user)
            ->post(route('wisatawan.ulasan.store', $completed), [
                'rating' => 9,
                'komentar' => 'pendek',
            ])
            ->assertSessionHasErrors(['rating', 'komentar']);

        $this->assertSame(0, Ulasan::count());
    }

    public function test_tourist_booking_index_only_lists_own_bookings(): void
    {
        [$user, $wisatawan] = $this->wisatawanUser();
        [, $lain] = $this->wisatawanUser();
        $kamar = $this->room();

        Booking::factory()->create([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
        ]);

        Booking::factory()->create([
            'id_wisatawan' => $lain->id_wisatawan,
            'id_kamar' => $kamar->id_kamar,
            'tgl_checkin' => now()->addDays(20)->toDateString(),
            'tgl_checkout' => now()->addDays(22)->toDateString(),
        ]);

        $this->actingAs($user)
            ->get(route('wisatawan.booking.index'))
            ->assertOk()
            ->assertSee('Homestay')
            ->assertSee('Lihat detail');
    }
}
