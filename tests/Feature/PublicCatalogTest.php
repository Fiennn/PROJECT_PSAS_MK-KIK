<?php

namespace Tests\Feature;

use App\Enums\StatusBooking;
use App\Models\Booking;
use App\Models\Fasilitas;
use App\Models\Homestay;
use App\Models\Kamar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_shows_only_approved_homestays(): void
    {
        $approved = Homestay::factory()->create(['nama_homestay' => 'Homestay Disetujui']);
        Homestay::factory()->menunggu()->create(['nama_homestay' => 'Homestay Menunggu']);
        Kamar::factory()->create(['id_homestay' => $approved->id_homestay]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Homestay Disetujui')
            ->assertDontSee('Homestay Menunggu');
    }

    public function test_catalog_filters_by_price_capacity_and_facilities(): void
    {
        $cheap = Homestay::factory()->create(['nama_homestay' => 'Homestay Murah']);
        $cheapRoom = Kamar::factory()->create([
            'id_homestay' => $cheap->id_homestay,
            'harga_per_malam' => 150000,
            'kapasitas' => 2,
        ]);
        $wifi = Fasilitas::factory()->create(['nama_fasilitas' => 'WiFi']);
        $cheap->fasilitas()->attach($wifi->id_fasilitas);

        $expensive = Homestay::factory()->create(['nama_homestay' => 'Homestay Mahal']);
        Kamar::factory()->create([
            'id_homestay' => $expensive->id_homestay,
            'harga_per_malam' => 750000,
            'kapasitas' => 4,
        ]);

        $this->get('/homestay?harga_max=300000&kapasitas=2')
            ->assertOk()
            ->assertSee('Homestay Murah')
            ->assertDontSee('Homestay Mahal');

        $this->get('/homestay?fasilitas[]='.$wifi->id_fasilitas)
            ->assertOk()
            ->assertSee('Homestay Murah')
            ->assertDontSee('Homestay Mahal');
    }

    public function test_approved_homestay_detail_can_be_opened(): void
    {
        $homestay = Homestay::factory()->create(['nama_homestay' => 'Homestay Detail']);
        Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]);

        $this->get(route('homestay.show', $homestay))
            ->assertOk()
            ->assertSee('Homestay Detail')
            ->assertSee('Cek ketersediaan');
    }

    public function test_unapproved_homestay_detail_returns_not_found(): void
    {
        $homestay = Homestay::factory()->menunggu()->create();

        $this->get(route('homestay.show', $homestay))->assertNotFound();
    }

    public function test_availability_marks_room_as_unavailable_for_overlapping_dates(): void
    {
        $homestay = Homestay::factory()->create();
        $room = Kamar::factory()->create([
            'id_homestay' => $homestay->id_homestay,
            'kapasitas' => 2,
            'harga_per_malam' => 200000,
        ]);

        Booking::factory()->create([
            'id_kamar' => $room->id_kamar,
            'tgl_checkin' => now()->addDays(10)->toDateString(),
            'tgl_checkout' => now()->addDays(12)->toDateString(),
            'status' => StatusBooking::Dikonfirmasi->value,
        ]);

        $overlap = $this->getJson(route('homestay.availability', [
            'homestay' => $homestay,
            'checkin' => now()->addDays(11)->toDateString(),
            'checkout' => now()->addDays(13)->toDateString(),
        ]));

        $overlap->assertOk()->assertJsonPath('rooms.0.tersedia', false);

        $touching = $this->getJson(route('homestay.availability', [
            'homestay' => $homestay,
            'checkin' => now()->addDays(12)->toDateString(),
            'checkout' => now()->addDays(14)->toDateString(),
        ]));

        $touching->assertOk()->assertJsonPath('rooms.0.tersedia', true);
    }

    public function test_availability_rejects_invalid_dates(): void
    {
        $homestay = Homestay::factory()->create();

        $this->getJson(route('homestay.availability', [
            'homestay' => $homestay,
            'checkin' => now()->subDay()->toDateString(),
            'checkout' => now()->toDateString(),
        ]))->assertUnprocessable()->assertJsonValidationErrors('checkin');
    }

    public function test_catalog_filters_by_suasana_experience(): void
    {
        $sawah = Homestay::factory()->create(['nama_homestay' => 'Griya Sawah Terasering Indah']);
        Kamar::factory()->create(['id_homestay' => $sawah->id_homestay]);

        $gunung = Homestay::factory()->create(['nama_homestay' => 'Villa Puncak Lereng Gunung']);
        Kamar::factory()->create(['id_homestay' => $gunung->id_homestay]);

        $this->get('/homestay?suasana=sawah')
            ->assertOk()
            ->assertSee('Griya Sawah Terasering Indah')
            ->assertDontSee('Villa Puncak Lereng Gunung');
    }

    public function test_homestay_show_renders_google_maps_route_and_etiquette(): void
    {
        $homestay = Homestay::factory()->create(['nama_homestay' => 'Homestay Harmoni Desa']);
        Kamar::factory()->create(['id_homestay' => $homestay->id_homestay]);

        $this->get(route('homestay.show', $homestay))
            ->assertOk()
            ->assertSee('Buka Rute di Google Maps')
            ->assertSee('Kearifan Lokal Desa');
    }
}

