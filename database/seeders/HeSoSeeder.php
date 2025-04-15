<?php

namespace Database\Seeders;

use App\Models\HeSo;
use Illuminate\Database\Seeder;

class HeSoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HeSo::factory()->create([
            'luongcb' => 1500000,
            'bac1' => 1.0,
            'bac2' => 1.2,
            'bac3' => 1.4,
            'bac4' => 1.6,
            'bac5' => 1.8,
            'bac6' => 1.9,
            'bac7' => 2.0,
            'bac8' => 2.2,
            'bac9' => 2.4,
            'bac10' => 2.6,
        ]);
    }
}
