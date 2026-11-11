<?php

namespace Tests\Feature;

use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use App\Enums\StatusVerifikasi;
use App\Models\Booking;
use App\Models\Fasilitas;
use App\Models\Homestay;
use App\Models\Kamar;
use App\Models\PemilikHomestay;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Wisatawan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    private function homestay(array $attributes = []): Homestay
    {
        $owner = User::factory()->pemilik()->create();
        $pemilik = PemilikHomestay::factory()->create(['id_user' => $owner->id_user]);

        return Homestay::factory()->create(array_merge([
            'id_pemilik' => $pemilik->id_pemilik,
            'status_verifikasi' => StatusVerifikasi::Menunggu->value,
        ], $attributes));
    }

    private function bookingWithPayment(array $bookingAttributes = [], array $paymentAttributes = []): array
    {
        $room = Kamar::factory()->create(['id_homestay' => $this->homestay(['status_verifikasi' => StatusVerifikasi::Disetujui->value])->id_homestay]);
        $wisatawan = Wisatawan::factory()->create();

        $booking = Booking::factory()->create(array_merge([
            'id_wisatawan' => $wisatawan->id_wisatawan,
            'id_kamar' => $room->id_kamar,
            'status' => StatusBooking::MenungguVerifikasi->value,
            'total_harga' => 600000,
        ], $bookingAttributes));

        $payment = Pembayaran::factory()->create(array_merge([
            'id_booking' => $booking->id_booking,
            'jumlah' => 600000,
        ], $paymentAttributes));

        return [$booking, $payment];
    }

    public function test_non_admin_cannot_access_admin_area(): void
    {
        $tourist = User::factory()->wisatawan()->create();
        $owner = User::factory()->pemilik()->create();

        $this->actingAs($tourist)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($owner)->get(route('admin.pembayaran.index'))->assertForbidden();
        $this->actingAs($tourist)->get(route('admin.laporan.booking'))->assertForbidden();
    }

    public function test_admin_can_list_and_filter_users(): void
    {
        $admin = $this->admin();
        $owner = User::factory()->pemilik()->create(['username' => 'pemilik-uji']);
        $tourist = User::factory()->wisatawan()->create(['username' => 'wisatawan-uji']);
        Wisatawan::factory()->create(['id_user' => $tourist->id_user, 'nama_wisatawan' => 'Budi Santoso']);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee($admin->username)
            ->assertSee('pemilik-uji');

        $this->actingAs($admin)
            ->get(route('admin.users.index', ['role' => 'wisatawan']))
            ->assertOk()
            ->assertSee('wisatawan-uji')
            ->assertDontSee('pemilik-uji');

        $this->assertTrue($owner->status_aktif);
    }

    public function test_admin_can_toggle_user_status_but_not_own_account(): void
    {
        $admin = $this->admin();
        $user = User::factory()->wisatawan()->create();

        $this->actingAs($admin)
            ->patch(route('admin.users.status', $user))
            ->assertRedirect();

        $this->assertFalse($user->fresh()->status_aktif);

        $this->actingAs($admin)
            ->patch(route('admin.users.status', $user))
            ->assertRedirect();

        $this->assertTrue($user->fresh()->status_aktif);

        $this->actingAs($admin)
            ->patch(route('admin.users.status', $admin))
            ->assertForbidden();

        $this->assertTrue($admin->fresh()->status_aktif);
    }

    public function test_admin_can_approve_and_reject_homestay_submissions(): void
    {
        $admin = $this->admin();
        $homestay = $this->homestay();
        $other = $this->homestay();

        $this->actingAs($admin)
            ->patch(route('admin.homestay.approve', $homestay))
            ->assertRedirect(route('admin.homestay.show', $homestay));

        $this->assertSame(StatusVerifikasi::Disetujui, $homestay->fresh()->status_verifikasi);

        $this->actingAs($admin)
            ->patch(route('admin.homestay.reject', $other))
            ->assertRedirect(route('admin.homestay.show', $other));

        $this->assertSame(StatusVerifikasi::Ditolak, $other->fresh()->status_verifikasi);
    }

    public function test_approved_homestay_appears_in_public_catalog(): void
    {
        $admin = $this->admin();
        $homestay = $this->homestay(['nama_homestay' => 'Homestay Uji Publik']);

        $this->actingAs($admin)->patch(route('admin.homestay.approve', $homestay));

        $this->get(route('homestay.index'))->assertOk()->assertSee('Homestay Uji Publik');
    }

    public function test_admin_can_create_new_homestay_directly(): void
    {
        $admin = $this->admin();
        $owner = User::factory()->pemilik()->create();
        $pemilik = PemilikHomestay::factory()->create(['id_user' => $owner->id_user]);
        $facility = Fasilitas::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.homestay.create'))
            ->assertOk()
            ->assertSee('Tambah Homestay Baru');

        $response = $this->actingAs($admin)->post(route('admin.homestay.store'), [
            'id_pemilik' => $pemilik->id_pemilik,
            'nama_homestay' => 'Homestay Dibuat Admin',
            'deskripsi' => 'Deskripsi homestay oleh admin desa.',
            'alamat' => 'Desa Wisata Indah RT 01',
            'status_verifikasi' => StatusVerifikasi::Disetujui->value,
            'fasilitas' => [$facility->id_fasilitas],
        ]);

        $homestay = Homestay::where('nama_homestay', 'Homestay Dibuat Admin')->firstOrFail();
        $response->assertRedirect(route('admin.homestay.show', $homestay));
        $this->assertSame(StatusVerifikasi::Disetujui, $homestay->status_verifikasi);
        $this->assertTrue($homestay->fasilitas->contains($facility));
    }

    public function test_admin_can_update_any_homestay_details(): void
    {
        $admin = $this->admin();
        $homestay = $this->homestay();

        $this->actingAs($admin)
            ->get(route('admin.homestay.edit', $homestay))
            ->assertOk()
            ->assertSee($homestay->nama_homestay);

        $response = $this->actingAs($admin)->put(route('admin.homestay.update', $homestay), [
            'id_pemilik' => $homestay->id_pemilik,
            'nama_homestay' => 'Homestay Diperbarui Admin',
            'deskripsi' => 'Deskripsi baru setelah diubah.',
            'alamat' => 'Alamat baru desa',
            'status_verifikasi' => StatusVerifikasi::Disetujui->value,
        ]);

        $response->assertRedirect(route('admin.homestay.show', $homestay));
        $this->assertSame('Homestay Diperbarui Admin', $homestay->fresh()->nama_homestay);
        $this->assertSame(StatusVerifikasi::Disetujui, $homestay->fresh()->status_verifikasi);
    }

    public function test_admin_can_delete_homestay_without_bookings(): void
    {
        $admin = $this->admin();
        $homestay = $this->homestay();

        $response = $this->actingAs($admin)->delete(route('admin.homestay.destroy', $homestay));
        $response->assertRedirect(route('admin.homestay.index'));
        $this->assertDatabaseMissing('homestay', ['id_homestay' => $homestay->id_homestay]);
    }

    public function test_admin_can_verify_pending_payment(): void
    {
        $admin = $this->admin();
        [$booking, $payment] = $this->bookingWithPayment();

        $this->actingAs($admin)
            ->patch(route('admin.pembayaran.verify', $payment))
            ->assertRedirect(route('admin.pembayaran.show', $payment));

        $this->assertSame(StatusPembayaran::Terverifikasi, $payment->fresh()->status_verifikasi);
        $this->assertSame(StatusBooking::MenungguVerifikasi, $booking->fresh()->status);
    }

    public function test_admin_cannot_verify_payment_twice(): void
    {
        $admin = $this->admin();
        [, $payment] = $this->bookingWithPayment([], ['status_verifikasi' => StatusPembayaran::Terverifikasi->value]);

        $this->actingAs($admin)
            ->patch(route('admin.pembayaran.verify', $payment))
            ->assertForbidden();
    }

    public function test_rejecting_payment_returns_booking_to_waiting_payment(): void
    {
        $admin = $this->admin();
        [$booking, $payment] = $this->bookingWithPayment();

        $this->actingAs($admin)
            ->patch(route('admin.pembayaran.reject', $payment))
            ->assertRedirect(route('admin.pembayaran.show', $payment));

        $this->assertSame(StatusPembayaran::Ditolak, $payment->fresh()->status_verifikasi);
        $this->assertSame(StatusBooking::MenungguPembayaran, $booking->fresh()->status);
    }

    public function test_rejecting_payment_does_not_change_already_confirmed_booking(): void
    {
        $admin = $this->admin();
        [$booking, $payment] = $this->bookingWithPayment([
            'status' => StatusBooking::Dikonfirmasi->value,
        ]);

        $this->actingAs($admin)->patch(route('admin.pembayaran.reject', $payment));

        $this->assertSame(StatusBooking::Dikonfirmasi, $booking->fresh()->status);
    }

    public function test_admin_can_manage_facilities(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.fasilitas.store'), ['nama_fasilitas' => 'Kolam Renang'])
            ->assertRedirect(route('admin.fasilitas.index'));

        $facility = Fasilitas::where('nama_fasilitas', 'Kolam Renang')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.fasilitas.store'), ['nama_fasilitas' => 'Kolam Renang'])
            ->assertSessionHasErrors('nama_fasilitas');

        $this->actingAs($admin)
            ->patch(route('admin.fasilitas.update', $facility), ['nama_fasilitas' => 'Kolam Renang Alami'])
            ->assertRedirect(route('admin.fasilitas.index'));

        $this->assertSame('Kolam Renang Alami', $facility->fresh()->nama_fasilitas);

        $this->actingAs($admin)
            ->delete(route('admin.fasilitas.destroy', $facility))
            ->assertRedirect(route('admin.fasilitas.index'));

        $this->assertDatabaseMissing('fasilitas', ['id_fasilitas' => $facility->id_fasilitas]);
    }

    public function test_facility_used_by_homestay_cannot_be_deleted(): void
    {
        $admin = $this->admin();
        $homestay = $this->homestay();
        $facility = Fasilitas::factory()->create(['nama_fasilitas' => 'Wifi']);
        $homestay->fasilitas()->attach($facility->id_fasilitas);

        $this->actingAs($admin)
            ->delete(route('admin.fasilitas.destroy', $facility))
            ->assertRedirect(route('admin.fasilitas.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('fasilitas', ['id_fasilitas' => $facility->id_fasilitas]);
    }

    public function test_report_pages_render_with_correct_totals(): void
    {
        $admin = $this->admin();
        [$confirmed] = $this->bookingWithPayment([
            'status' => StatusBooking::Dikonfirmasi->value,
            'total_harga' => 500000,
            'tgl_booking' => '2026-06-01',
            'tgl_checkin' => '2026-06-10',
            'tgl_checkout' => '2026-06-12',
        ]);
        [$completed] = $this->bookingWithPayment([
            'status' => StatusBooking::Selesai->value,
            'total_harga' => 750000,
            'tgl_booking' => '2026-06-02',
            'tgl_checkin' => '2026-06-15',
            'tgl_checkout' => '2026-06-17',
        ]);
        [$rejected] = $this->bookingWithPayment([
            'status' => StatusBooking::Ditolak->value,
            'total_harga' => 999000,
            'tgl_booking' => '2026-06-03',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.laporan.booking', ['dari' => '2026-06-01', 'sampai' => '2026-06-30']))
            ->assertOk()
            ->assertSee('Laporan Booking')
            ->assertSee($confirmed->wisatawan->nama_wisatawan)
            ->assertSee($rejected->wisatawan->nama_wisatawan);

        $this->actingAs($admin)
            ->get(route('admin.laporan.pendapatan', ['dari' => '2026-06-01', 'sampai' => '2026-06-30']))
            ->assertOk()
            ->assertViewHas('total', fn ($total) => (float) $total === 1250000.0)
            ->assertSee($completed->kamar->homestay->nama_homestay);
    }

    public function test_reports_validate_date_range(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.laporan.booking', ['dari' => '2026-06-10', 'sampai' => '2026-06-01']))
            ->assertSessionHasErrors('sampai');
    }

    public function test_admin_pages_render_successfully(): void
    {
        $admin = $this->admin();
        [$booking, $payment] = $this->bookingWithPayment();
        $homestay = $booking->kamar->homestay;
        $facility = Fasilitas::factory()->create();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.homestay.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.homestay.show', $homestay))->assertOk();
        $this->actingAs($admin)->get(route('admin.pembayaran.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.pembayaran.show', $payment))->assertOk();
        $this->actingAs($admin)->get(route('admin.fasilitas.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.laporan.booking'))->assertOk();
        $this->actingAs($admin)->get(route('admin.laporan.pendapatan'))->assertOk();
    }
}
