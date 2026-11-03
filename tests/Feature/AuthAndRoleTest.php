<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use App\Models\Wisatawan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get('/wisatawan/dashboard')->assertRedirect('/login');
    }

    public function test_user_can_login_with_username(): void
    {
        $user = User::factory()->wisatawan()->create([
            'username' => 'ayu',
            'password' => 'rahasia123',
        ]);

        Wisatawan::factory()->create(['id_user' => $user->id_user]);

        $response = $this->post('/login', [
            'username' => 'ayu',
            'password' => 'rahasia123',
        ]);

        $response->assertRedirect(route('wisatawan.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->wisatawan()->create(['username' => 'ayu', 'password' => 'rahasia123']);

        $this->from('/login')->post('/login', [
            'username' => 'ayu',
            'password' => 'salah',
        ])->assertRedirect('/login')->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->wisatawan()->nonaktif()->create([
            'username' => 'nonaktif',
            'password' => 'rahasia123',
        ]);

        $this->post('/login', [
            'username' => 'nonaktif',
            'password' => 'rahasia123',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        User::factory()->wisatawan()->create(['username' => 'ayu', 'password' => 'rahasia123']);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', ['username' => 'ayu', 'password' => 'salah']);
        }

        $response = $this->post('/login', ['username' => 'ayu', 'password' => 'salah']);
        $response->assertSessionHasErrors('username');
        $this->assertStringContainsString('Terlalu banyak', session('errors')->first('username'));
    }

    public function test_registration_creates_wisatawan_profile(): void
    {
        $response = $this->post('/register', [
            'username' => 'wisatawanbaru',
            'email' => 'baru@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => Role::Wisatawan->value,
            'nama_wisatawan' => 'Wisatawan Baru',
            'no_hp' => '081234567890',
            'asal_kota' => 'Bandung',
        ]);

        $user = User::where('username', 'wisatawanbaru')->firstOrFail();

        $response->assertRedirect(route('wisatawan.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('wisatawan', [
            'id_user' => $user->id_user,
            'nama_wisatawan' => 'Wisatawan Baru',
        ]);
    }

    public function test_role_middleware_blocks_wrong_role(): void
    {
        $wisatawan = User::factory()->wisatawan()->create();

        $this->actingAs($wisatawan)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($wisatawan)->get('/pemilik/dashboard')->assertForbidden();
    }

    public function test_each_role_can_open_its_dashboard(): void
    {
        $wisatawanUser = User::factory()->wisatawan()->create();
        Wisatawan::factory()->create(['id_user' => $wisatawanUser->id_user]);
        $this->actingAs($wisatawanUser)->get('/wisatawan/dashboard')->assertOk();

        $pemilikUser = User::factory()->pemilik()->create();
        \App\Models\PemilikHomestay::factory()->create(['id_user' => $pemilikUser->id_user]);
        $this->actingAs($pemilikUser)->get('/pemilik/dashboard')->assertOk();

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
    }

    public function test_deactivated_user_is_logged_out_on_next_request(): void
    {
        $user = User::factory()->wisatawan()->create(['status_aktif' => false]);
        Wisatawan::factory()->create(['id_user' => $user->id_user]);

        $this->actingAs($user)->get('/wisatawan/dashboard')->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
