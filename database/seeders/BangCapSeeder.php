<?php

namespace Database\Seeders;

use App\Models\BangCap;
use Illuminate\Database\Seeder;

class BangCapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BangCap::factory()->create([
            'tenbc' => 'Tiến Sĩ'
        ]);

        BangCap::factory()->create([
            'tenbc' => 'Thạc Sĩ'
        ]);

        BangCap::factory()->create([
            'tenbc' => 'Cử Nhân'
        ]);

        BangCap::factory()->create([
            'tenbc' => 'Đại Học'
        ]);

        BangCap::factory()->create([
            'tenbc' => 'Cao Đẳng'
        ]);
    }
}
