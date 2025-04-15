<?php

namespace Database\Seeders;

use App\Models\NgoaiNgu;
use Illuminate\Database\Seeder;

class NgoaiNguSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NgoaiNgu::factory()->create([
            'tenng' => 'Sơ cấp - Bậc 1 (A1)'
        ]);

        NgoaiNgu::factory()->create([
            'tenng' => 'Sơ cấp - Bậc 2 (A2)'
        ]);

        NgoaiNgu::factory()->create([
            'tenng' => 'Trung cấp - Bậc 1 (B1)'
        ]);

        NgoaiNgu::factory()->create([
            'tenng' => 'Trung cấp - Bậc 2 (B2)'
        ]);

        NgoaiNgu::factory()->create([
            'tenng' => 'Cao cấp - Bậc 1 (C1)'
        ]);

        NgoaiNgu::factory()->create([
            'tenng' => 'Cao cấp - Bậc 2 (C2)'
        ]);
    }
}
