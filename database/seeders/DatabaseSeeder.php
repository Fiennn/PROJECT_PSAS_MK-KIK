<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FasilitasSeeder::class,
            HomestaySeeder::class,
            BookingSeeder::class,
        ]);
    }
}
