<?php

namespace Database\Seeders;

use App\Models\ChuyenMon;
use Illuminate\Database\Seeder;

class ChuyenMonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChuyenMon::factory()->create([
            'tencm' => 'Programmer'
        ]);

        ChuyenMon::factory()->create([
            'tencm' => 'Tester'
        ]);

        ChuyenMon::factory()->create([
            'tencm' => 'Front-end'
        ]);

        ChuyenMon::factory()->create([
            'tencm' => 'Back-end'
        ]);

        ChuyenMon::factory()->create([
            'tencm' => 'Full-Stack'
        ]);
    }
}
