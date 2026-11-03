<?php

namespace Tests\Feature;

use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use App\Enums\StatusVerifikasi;
use App\Models\Booking;
use App\Models\Fasilitas;
use App\Models\Homestay;
use App\Models\Kamar;
use App\Models\PaketWisata;
use App\Models\PemilikHomestay;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Wisatawan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function owner(): array
    {
        $user = User::factory()->pemilik()->create();
        $pemilik = PemilikHomestay::factory()->create(['id_user' => $user->id_user]);

        return [$user, $pemilik];
    }

    private function homestayFor(PemilikHomestay $pemilik, array $attributes = []): Homestay
    {
        return Homestay::factory()->create(array_merge(['id_pemilik' => $pemilik->id_pemilik], $attributes));
    }

    public function test_owner_can_create_homestay_with_facilities_and_pending_status(): void
    {
        [$user] = $this->owner();
        $wifi = Fasilitas::factory()->create(['nama_fasilitas' => 'WiFi']);
        $parkir = Fasilitas::factory()->create(['nama_fasilitas' => 'Parkir']);

        $response = $this->actingAs($user)->post(route('pemilik.homestay.store'), [
            'nama_homestay' => 'Homestay Baru',
            'deskripsi' => 'Rumah kayu dengan pemandangan sawah dan suasana desa yang tenang.',
            'alamat' => 'Desa Penglipuran, Bangli, Bali',
            'fasilitas' => [$wifi->id_fasilitas, $parkir->id_fasilitas],
        ]);

        $homestay = Homestay::where('nama_homestay', 'Homestay Baru')->firstOrFail();

        $response->assertRedirect(route('pemilik.homestay.index'));
        $this->assertSame(StatusVerifikasi::Menunggu, $homestay->status_verifikasi);
        $this->assertCount(2, $homestay->fasilitas);
    }

    public function test_homestay_creation_requires_complete_data(): void
    {
        [$user] = $this->owner();

        $this->actingAs($user)
            ->post(route('pemilik.homestay.store'), [
                'nama_homestay' => '',
                'deskripsi' => 'pendek',
                'alamat' => 'x',
            ])
            ->assertSessionHasErrors(['nama_homestay', 'deskripsi', 'alamat']);

        $this->assertSame(0, Homestay::count());
    }

    public function test_owner_cannot_edit_another_owners_homestay(): void
    {
        [$user] = $this->owner();
        [, $lain] = $this->owner();
        $homestay = $this->homestayFor($lain);

        $this->actingAs($user)->get(route('pemilik.homestay.edit', $homestay))->assertForbidden();
        $this->actingAs($user)->patch(route('pemilik.homestay.update', $homestay), [
            'nama_homestay' => 'Diambil Alih',
            'deskripsi' => 'Deskripsi baru yang cukup panjang untuk lolos validasi.',
            'alamat' => 'Alamat baru yang juga cukup panjang',
        ])->assertForbidden();

        $this->assertNotSame('Diambil Alih', $homestay->fresh()->nama_homestay);
    }

    public function test_rejected_homestay_returns_to_pending_after_update(): void
    {
        [$user, $pemilik] = $this->owner();
        $homestay = $this->homestayFor($pemilik, ['status_verifikasi' => StatusVerifikasi::Ditolak->value]);

        $this->actingAs($user)->patch(route('pemilik.homestay.update', $homestay), [
            'nama_homestay' => $homestay->nama_homestay,
            'deskripsi' => 'Deskripsi yang sudah diperbaiki dan cukup panjang.',
            'alamat' => 'Alamat yang sudah diperbaiki dan lengkap',
            'fasilitas' => [],
        ])->assertRedirect(route('pemilik.homestay.index'));

        $this->assertSame(StatusVerifikasi::Menunggu, $homestay->fresh()->status_verifikasi);
    }

    public function test_owner_can_manage_rooms_for_own_homestay_only(): void
    {
        [$user, $pemilik] = $this->owner();
        $homestay = $this->homestayFor($pemilik);
        [, $lain] = $this->owner();
        $lainHomestay = $this->homestayFor($lain);

        $this->actingAs($user)->post(route('pemilik.homestay.kamar.store', $homestay), [
            'nama_kamar' => 'Kamar Baru',
            'kapasitas' => 2,
            'harga_per_malam' => 175000,
            'status' => 'tersedia',
        ])->assertRedirect(route('pemilik.homestay.kamar.index', $homestay));

        $this->assertDatabaseHas('kamar', [
            'id_homestay' => $homestay->id_homestay,
            'nama_kamar' => 'Kamar Baru',
        ]);

        $this->actingAs($user)->post(route('pemilik.homestay.kamar.store', $lainHomestay), [
            'nama_kamar' => 'Kamar Ilegal',
            'kapasitas' => 2,
            'harga_per_malam' => 100000,
            'status' => 'tersedia',
        ])->assertForbidden();
    }

    public function test_room_with_booking_history_cannot_be_deleted_but_unused_room_can(): void
    {
        [$user, $pemilik] = $this->owner();
        $homestay = $this->homestayFor($pemilik);
        $roomWithBooking = Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]);
        $booking = Booking::factory()->create([
            'id_kamar' => $roomWithBooking->id_kamar,
            'status' => StatusBooking::Dikonfirmasi->value,
        ]);

        $this->actingAs($user)
            ->delete(route('pemilik.kamar.destroy', $roomWithBooking))
            ->assertForbidden();

        $this->assertDatabaseHas('kamar', ['id_kamar' => $roomWithBooking->id_kamar]);

        $booking->update(['status' => StatusBooking::Dibatalkan->value]);

        $this->actingAs($user)
            ->delete(route('pemilik.kamar.destroy', $roomWithBooking))
            ->assertForbidden();

        $this->assertDatabaseHas('kamar', ['id_kamar' => $roomWithBooking->id_kamar]);

        $unused = Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]);

        $this->actingAs($user)
            ->delete(route('pemilik.kamar.destroy', $unused))
            ->assertRedirect(route('pemilik.homestay.kamar.index', $homestay));

        $this->assertDatabaseMissing('kamar', ['id_kamar' => $unused->id_kamar]);
    }

    public function test_owner_can_manage_packages_and_cannot_delete_booked_package(): void
    {
        [$user, $pemilik] = $this->owner();
        $homestay = $this->homestayFor($pemilik);

        $this->actingAs($user)->post(route('pemilik.homestay.paket.store', $homestay), [
            'nama_paket' => 'Kelas Memasak',
            'deskripsi' => 'Belajar memasak hidangan khas desa bersama warga.',
            'harga' => 90000,
            'durasi' => '3 jam',
        ])->assertRedirect(route('pemilik.homestay.paket.index', $homestay));

        $package = PaketWisata::where('nama_paket', 'Kelas Memasak')->firstOrFail();

        $booking = Booking::factory()->create([
            'id_kamar' => Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]),
        ]);
        $booking->paket()->attach($package->id_paket, ['jumlah_peserta' => 2, 'subtotal' => 180000]);

        $this->actingAs($user)
            ->delete(route('pemilik.paket.destroy', $package))
            ->assertForbidden();

        $this->assertDatabaseHas('paket_wisata', ['id_paket' => $package->id_paket]);
    }

    public function test_owner_can_confirm_booking_after_payment_is_verified(): void
    {
        [$user, $pemilik] = $this->owner();
        $homestay = $this->homestayFor($pemilik);
        $booking = Booking::factory()->create([
            'id_kamar' => Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]),
            'status' => StatusBooking::MenungguVerifikasi->value,
        ]);

        Pembayaran::factory()->terverifikasi()->create(['id_booking' => $booking->id_booking]);

        $this->actingAs($user)
            ->patch(route('pemilik.booking.confirm', $booking))
            ->assertRedirect(route('pemilik.booking.show', $booking));

        $this->assertSame(StatusBooking::Dikonfirmasi, $booking->fresh()->status);
    }

    public function test_owner_cannot_confirm_without_verified_payment(): void
    {
        [$user, $pemilik] = $this->owner();
        $homestay = $this->homestayFor($pemilik);
        $booking = Booking::factory()->create([
            'id_kamar' => Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]),
            'status' => StatusBooking::MenungguVerifikasi->value,
        ]);

        Pembayaran::factory()->create([
            'id_booking' => $booking->id_booking,
            'status_verifikasi' => StatusPembayaran::Menunggu->value,
        ]);

        $this->actingAs($user)
            ->patch(route('pemilik.booking.confirm', $booking))
            ->assertForbidden();

        $this->assertSame(StatusBooking::MenungguVerifikasi, $booking->fresh()->status);
    }

    public function test_owner_can_reject_a_waiting_booking(): void
    {
        [$user, $pemilik] = $this->owner();
        $homestay = $this->homestayFor($pemilik);
        $booking = Booking::factory()->create([
            'id_kamar' => Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]),
            'status' => StatusBooking::MenungguVerifikasi->value,
        ]);

        $this->actingAs($user)
            ->patch(route('pemilik.booking.reject', $booking))
            ->assertRedirect(route('pemilik.booking.show', $booking));

        $this->assertSame(StatusBooking::Ditolak, $booking->fresh()->status);
    }

    public function test_owner_can_complete_booking_only_after_checkout_date(): void
    {
        [$user, $pemilik] = $this->owner();
        $homestay = $this->homestayFor($pemilik);
        $room = Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]);

        $future = Booking::factory()->create([
            'id_kamar' => $room->id_kamar,
            'status' => StatusBooking::Dikonfirmasi->value,
            'tgl_checkin' => now()->addDays(3)->toDateString(),
            'tgl_checkout' => now()->addDays(5)->toDateString(),
        ]);

        $this->actingAs($user)
            ->patch(route('pemilik.booking.complete', $future))
            ->assertForbidden();

        $past = Booking::factory()->create([
            'id_kamar' => $room->id_kamar,
            'status' => StatusBooking::Dikonfirmasi->value,
            'tgl_checkin' => now()->subDays(3)->toDateString(),
            'tgl_checkout' => now()->subDay()->toDateString(),
        ]);

        $this->actingAs($user)
            ->patch(route('pemilik.booking.complete', $past))
            ->assertRedirect(route('pemilik.booking.show', $past));

        $this->assertSame(StatusBooking::Selesai, $past->fresh()->status);
    }

    public function test_owner_cannot_view_another_owners_booking(): void
    {
        [$user] = $this->owner();
        [$lainUser, $lainPemilik] = $this->owner();
        $homestay = $this->homestayFor($lainPemilik);
        $booking = Booking::factory()->create([
            'id_kamar' => Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]),
        ]);

        $this->actingAs($user)->get(route('pemilik.booking.show', $booking))->assertForbidden();
        $this->actingAs($lainUser)->get(route('pemilik.booking.show', $booking))->assertOk();
    }

    public function test_revenue_page_totals_confirmed_and_completed_bookings_only(): void
    {
        [$user, $pemilik] = $this->owner();
        $homestay = $this->homestayFor($pemilik);
        $room = Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]);
        $wisatawan = Wisatawan::factory()->create();

        $base = [
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $room->id_kamar,
            'tgl_checkin' => '2026-05-10',
            'tgl_checkout' => '2026-05-12',
        ];

        Booking::factory()->create(array_merge($base, [
            'status' => StatusBooking::Dikonfirmasi->value,
            'total_harga' => 500000,
        ]));

        Booking::factory()->create(array_merge($base, [
            'status' => StatusBooking::Selesai->value,
            'total_harga' => 750000,
        ]));

        Booking::factory()->create(array_merge($base, [
            'status' => StatusBooking::Ditolak->value,
            'total_harga' => 999000,
        ]));

        $response = $this->actingAs($user)->get(route('pemilik.pendapatan', [
            'dari' => '2026-05-01',
            'sampai' => '2026-05-31',
        ]));

        $response->assertOk()->assertViewHas('totals', function (array $totals) {
            return (float) $totals['pendapatan'] === 1250000.0 && $totals['booking'] === 2;
        });
    }

    public function test_non_owner_cannot_access_owner_area(): void
    {
        $tourist = User::factory()->wisatawan()->create();

        $this->actingAs($tourist)->get(route('pemilik.homestay.index'))->assertForbidden();
        $this->actingAs($tourist)->get(route('pemilik.pendapatan'))->assertForbidden();
    }

    public function test_owner_pages_render_successfully(): void
    {
        [$user, $pemilik] = $this->owner();
        $homestay = $this->homestayFor($pemilik);
        $room = Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]);
        $package = PaketWisata::factory()->create(['id_homestay' => $homestay->id_homestay]);
        $booking = Booking::factory()->create(['id_kamar' => $room->id_kamar]);

        $this->actingAs($user)->get(route('pemilik.dashboard'))->assertOk();
        $this->actingAs($user)->get(route('pemilik.homestay.index'))->assertOk();
        $this->actingAs($user)->get(route('pemilik.homestay.create'))->assertOk();
        $this->actingAs($user)->get(route('pemilik.homestay.edit', $homestay))->assertOk();
        $this->actingAs($user)->get(route('pemilik.homestay.kamar.index', $homestay))->assertOk();
        $this->actingAs($user)->get(route('pemilik.kamar.edit', $room))->assertOk();
        $this->actingAs($user)->get(route('pemilik.homestay.paket.index', $homestay))->assertOk();
        $this->actingAs($user)->get(route('pemilik.paket.edit', $package))->assertOk();
        $this->actingAs($user)->get(route('pemilik.booking.index'))->assertOk();
        $this->actingAs($user)->get(route('pemilik.booking.show', $booking))->assertOk();
        $this->actingAs($user)->get(route('pemilik.pendapatan'))->assertOk();
    }
}
