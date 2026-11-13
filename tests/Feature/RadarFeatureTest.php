<?php

namespace Tests\Feature;

use App\Models\ArShelter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RadarFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed 3 test shelters in Banyumas
        ArShelter::create([
            'nama' => 'Bayan Village Test',
            'kategori' => 'Villa',
            'kecamatan' => 'KEDUNG BANTENG',
            'desa' => 'MELUNG',
            'alamat' => 'Dusun Melung',
            'latitude' => -7.3251620,
            'longitude' => 109.2182824,
            'no_hp' => '087782973064',
            'is_mitra_pokdarwis' => true,
        ]);

        ArShelter::create([
            'nama' => 'PJ Homestay Test',
            'kategori' => 'Homestay',
            'kecamatan' => 'BATURRADEN',
            'desa' => 'KETENGER',
            'alamat' => 'Desa Ketenger',
            'latitude' => -7.3301080,
            'longitude' => 109.2252055,
            'no_hp' => '081391624552',
            'is_mitra_pokdarwis' => true,
        ]);

        ArShelter::create([
            'nama' => 'Mruyung Guest House Test',
            'kategori' => 'Guest House',
            'kecamatan' => 'BANYUMAS',
            'desa' => 'SUDAGARAN',
            'alamat' => 'Kota Lama Banyumas',
            'latitude' => -7.5125617,
            'longitude' => 109.2937233,
            'no_hp' => '081225000225',
            'is_mitra_pokdarwis' => true,
        ]);
    }

    public function test_radar_page_renders_successfully(): void
    {
        $this->get(route('radar.index'))
            ->assertOk()
            ->assertSee('Radar AR Homestay')
            ->assertSee('WebAR')
            ->assertSee('Bayan Village Test')
            ->assertSee('PJ Homestay Test');
    }

    public function test_radar_api_returns_shelters_sorted_by_distance(): void
    {
        // Coordinate at Baturraden: close to PJ Homestay and Bayan Village, far from Mruyung
        $response = $this->getJson(route('radar.api', [
            'lat' => -7.3250,
            'lng' => 109.2200,
        ]));

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(3, 'data');

        $data = $response->json('data');

        // Bayan Village (-7.3251, 109.2182) should be the closest (< 0.5 km)
        $this->assertSame('Bayan Village Test', $data[0]['nama']);
        $this->assertLessThan(1.0, $data[0]['jarak_km']);
        // Mruyung (in Banyumas town, ~20km away) should be the farthest
        $this->assertSame('Mruyung Guest House Test', $data[2]['nama']);
        $this->assertGreaterThan(15.0, $data[2]['jarak_km']);
    }

    public function test_radar_api_filters_by_category(): void
    {
        $response = $this->getJson(route('radar.api', [
            'lat' => -7.3250,
            'lng' => 109.2200,
            'kategori' => 'Homestay',
        ]));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama', 'PJ Homestay Test');
    }

    public function test_radar_navbar_link_is_present(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('radar.index'))
            ->assertSee('Radar AR');
    }
}
