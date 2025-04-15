<?php

namespace Database\Seeders;

use App\Models\TonGiao;
use Illuminate\Database\Seeder;

class TonGiaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TonGiao::factory()->create([
            'tentg' => 'Phật giáo'
        ]);

        TonGiao::factory()->create([
            'tentg' => 'Công giáo'
        ]);

        TonGiao::factory()->create([
            'tentg' => 'Tin Lành'
        ]);

        TonGiao::factory()->create([
            'tentg' => 'Hồi giáo'
        ]);

        TonGiao::factory()->create([
            'tentg' => 'Cao Đài'
        ]);

        TonGiao::factory()->create([
            'tentg' => 'Hoà Hảo'
        ]);
    }
}
