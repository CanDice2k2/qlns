<?php

namespace Database\Seeders;

use App\Models\LoaiBaoHiem;
use Illuminate\Database\Seeder;

class LoaiBaoHiemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LoaiBaoHiem::factory()->create([
            'tenbh' => 'Bảo Hiểm Xã Hội'
        ]);

        LoaiBaoHiem::factory()->create([
            'tenbh' => 'Bảo Hiểm Y Tế'
        ]);

        LoaiBaoHiem::factory()->create([
            'tenbh' => 'Bảo Hiểm Tai Nạn'
        ]);

        LoaiBaoHiem::factory()->create([
            'tenbh' => 'Bảo Hiểm Thất Nghiệp'
        ]);
    }
}
