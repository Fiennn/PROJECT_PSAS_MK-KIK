<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => Str::lower(fake()->unique()->userName()),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => Role::Wisatawan->value,
            'status_aktif' => true,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => Role::Admin->value]);
    }

    public function pemilik(): static
    {
        return $this->state(fn () => ['role' => Role::Pemilik->value]);
    }

    public function wisatawan(): static
    {
        return $this->state(fn () => ['role' => Role::Wisatawan->value]);
    }

    public function nonaktif(): static
    {
        return $this->state(fn () => ['status_aktif' => false]);
    }
}
