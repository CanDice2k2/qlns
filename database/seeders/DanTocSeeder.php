<?php

namespace Database\Seeders;

use App\Models\DanToc;
use Illuminate\Database\Seeder;

class DanTocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DanToc::factory()->create([
            'tendt' => 'Kinh'
        ]);

        DanToc::factory()->create([
            'tendt' => 'Thái'
        ]);

        DanToc::factory()->create([
            'tendt' => 'Mường'
        ]);

        DanToc::factory()->create([
            'tendt' => 'Khmer'
        ]);

        DanToc::factory()->create([
            'tendt' => 'Hoa'
        ]);

        DanToc::factory()->create([
            'tendt' => 'Nùng'
        ]);

        DanToc::factory()->create([
            'tendt' => 'H\'Mông'
        ]);
    }
}
