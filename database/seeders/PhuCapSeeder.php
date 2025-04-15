<?php

namespace Database\Seeders;

use App\Models\ChucVu;
use App\Models\PhongBan;
use App\Models\PhuCap;
use Illuminate\Database\Seeder;

class PhuCapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $giamdoc = PhongBan::factory()->create([
            'tenpb' => 'Ban Giám Đốc'
        ]);

        $kinhdoanh = PhongBan::factory()->create([
            'tenpb' => 'Phòng Kinh Doanh'
        ]);

        $phantich = PhongBan::factory()->create([
            'tenpb' => 'Phòng Phân Tích'
        ]);

        $thietke = PhongBan::factory()->create([
            'tenpb' => 'Phòng Thiết Kế'
        ]);

        $laptrinh = PhongBan::factory()->create([
            'tenpb' => 'Phòng Lập Trình'
        ]);

        $hanhchinh = PhongBan::factory()->create([
            'tenpb' => 'Phòng Hành Chính'
        ]);

        $truongphong = ChucVu::factory()->create([
            'tencv' => 'Trưởng Phòng'
        ]);

        $truongphong = ChucVu::factory()->create([
            'tencv' => 'Trưởng Phòng'
        ]);

        $phophong = ChucVu::factory()->create([
            'tencv' => 'Phó Phòng'
        ]);

        $marketing = ChucVu::factory()->create([
            'tencv' => 'Marketing'
        ]);

        $nhanvien = ChucVu::factory()->create([
            'tencv' => 'Nhân Viên'
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $giamdoc,
            'chucvu_id' => $truongphong,
            'hsphucap' => 2.50
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $giamdoc,
            'chucvu_id' => $phophong,
            'hsphucap' => 2.0
        ]);

         PhuCap::factory()->create([
            'phongban_id' => $kinhdoanh,
            'chucvu_id' => $truongphong,
            'hsphucap' => 1.50
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $kinhdoanh,
            'chucvu_id' => $phophong,
            'hsphucap' => 1.20
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $kinhdoanh,
            'chucvu_id' => $marketing,
            'hsphucap' => 1.00
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $kinhdoanh,
            'chucvu_id' => $nhanvien,
            'hsphucap' => 1.00
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $phantich,
            'chucvu_id' => $truongphong,
            'hsphucap' => 1.50
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $phantich,
            'chucvu_id' => $phophong,
            'hsphucap' => 1.20
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $phantich,
            'chucvu_id' => $nhanvien,
            'hsphucap' => 1.00
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $thietke,
            'chucvu_id' => $truongphong,
            'hsphucap' => 1.50
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $thietke,
            'chucvu_id' => $phophong,
            'hsphucap' => 1.20
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $thietke,
            'chucvu_id' => $nhanvien,
            'hsphucap' => 1.00
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $laptrinh,
            'chucvu_id' => $truongphong,
            'hsphucap' => 1.50
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $laptrinh,
            'chucvu_id' => $phophong,
            'hsphucap' => 1.20
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $laptrinh,
            'chucvu_id' => $nhanvien,
            'hsphucap' => 1.00
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $hanhchinh,
            'chucvu_id' => $truongphong,
            'hsphucap' => 1.50
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $hanhchinh,
            'chucvu_id' => $phophong,
            'hsphucap' => 1.20
        ]);

        PhuCap::factory()->create([
            'phongban_id' => $hanhchinh,
            'chucvu_id' => $nhanvien,
            'hsphucap' => 1.00
        ]);
    }
}
