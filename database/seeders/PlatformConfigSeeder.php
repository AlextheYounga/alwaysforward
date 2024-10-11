<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlatformConfig;

class PlatformConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        PlatformConfig::truncate();

        PlatformConfig::create([
            'key' => 'birthday',
            'value' => env('BIRTHDAY') ?? '1995-11-13',
            'type' => 'string',
        ]);

        PlatformConfig::create([
            'key' => 'death_age',
            'value' => env('DEATH_AGE') ?? 90,
            'type' => 'integer',
        ]);

    }
}
