<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\WeekSeeder;
use Database\Seeders\LifeEventSeeder;
use Database\Seeders\PlatformConfigSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlatformConfigSeeder::class,
            WeekSeeder::class,
            LifeEventSeeder::class,
        ]);
    }
}
