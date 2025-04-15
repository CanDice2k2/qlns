<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PhuCapSeeder::class,
            ChuyenMonSeeder::class,
            BangCapSeeder::class,
            DanTocSeeder::class,
            TonGiaoSeeder::class,
            NgoaiNguSeeder::class,
            LoaiBaoHiemSeeder::class,
            HeSoSeeder::class,
            NhanVienSeeder::class
        ]);
    }
}
