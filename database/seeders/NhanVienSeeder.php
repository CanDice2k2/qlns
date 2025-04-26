<?php

namespace Database\Seeders;

use App\Models\NhanVien;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Faker\Factory;

class NhanVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo mảng các họ, tên đệm và tên tiếng Việt phổ biến
        $ho = [
            'Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng',
            'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương', 'Lý', 'Đào', 'Đinh', 'Trịnh', 'Mai', 'Cao'
        ];

        $tenDem = [
            'Văn', 'Thị', 'Hữu', 'Đức', 'Công', 'Quang', 'Minh', 'Hoài', 'Thanh', 'Xuân',
            'Hồng', 'Thị', 'Thị', 'Quốc', 'Anh', 'Tuấn', 'Thành', 'Kim', 'Đình', 'Mạnh'
        ];

        $ten = [
            'Hùng', 'Hà', 'Dũng', 'Thắng', 'Anh', 'Linh', 'Phương', 'Hương', 'Tùng', 'Nam',
            'Hiếu', 'Hải', 'Phong', 'Long', 'Trang', 'Vân', 'Hoa', 'Nhung', 'Nga', 'Lan',
            'Giang', 'Dương', 'Tú', 'Thảo', 'Loan', 'Mai', 'Tâm', 'Hạnh', 'Trung', 'Duy'
        ];

        // Tạo tài khoản admin cố định
        User::factory()->create([
            'nhanvien_id' => NhanVien::factory()->create([
                'phucap_id' => DB::table('phucap')->inRandomOrder()->first()->id,
                'bangcap_id' => DB::table('bangcap')->inRandomOrder()->first()->id,
                'chuyenmon_id' => DB::table('chuyenmon')->inRandomOrder()->first()->id,
                'ngoaingu_id' => DB::table('ngoaingu')->inRandomOrder()->first()->id,
                'dantoc_id' => DB::table('dantoc')->inRandomOrder()->first()->id,
                'tongiao_id' => DB::table('tongiao')->inRandomOrder()->first()->id,
                'hovaten' => 'Mai Văn Đức',
                'sdt' => '0944430146',
                'gioitinh' => false
            ]),
            'email' => 'admin@email.com',
            'role' => 2
        ]);

        // Tạo tài khoản quản lý cố định
        User::factory()->create([
            'nhanvien_id' => NhanVien::factory()->create([
                'phucap_id' => DB::table('phucap')->inRandomOrder()->first()->id,
                'bangcap_id' => DB::table('bangcap')->inRandomOrder()->first()->id,
                'chuyenmon_id' => DB::table('chuyenmon')->inRandomOrder()->first()->id,
                'ngoaingu_id' => DB::table('ngoaingu')->inRandomOrder()->first()->id,
                'dantoc_id' => DB::table('dantoc')->inRandomOrder()->first()->id,
                'tongiao_id' => DB::table('tongiao')->inRandomOrder()->first()->id,
                'hovaten' => 'Trần Minh Quân',
                'sdt' => '0934343444',
                'gioitinh' => false
            ]),
            'email' => 'quanly@email.com',
            'role' => 1
        ]);

        // Tạo tài khoản nhân viên cố định
        User::factory()->create([
            'nhanvien_id' => NhanVien::factory()->create([
                'phucap_id' => DB::table('phucap')->inRandomOrder()->first()->id,
                'bangcap_id' => DB::table('bangcap')->inRandomOrder()->first()->id,
                'chuyenmon_id' => DB::table('chuyenmon')->inRandomOrder()->first()->id,
                'ngoaingu_id' => DB::table('ngoaingu')->inRandomOrder()->first()->id,
                'dantoc_id' => DB::table('dantoc')->inRandomOrder()->first()->id,
                'tongiao_id' => DB::table('tongiao')->inRandomOrder()->first()->id,
                'hovaten' => 'Lê Quang Vinh',
                'sdt' => '09343430156',
                'gioitinh' => false
            ]),
            'email' => 'nhanvien@email.com',
            'role' => 0
        ]);

        // Tạo faker cho số điện thoại
        $faker = Factory::create('vi_VN');

        // Tạo 30 nhân viên với tên tiếng Việt ngẫu nhiên
        for($i=1; $i<=30; $i++)
        {
            // Tạo họ và tên ngẫu nhiên
            $hoNgauNhien = $ho[array_rand($ho)];
            $tenDemNgauNhien = $tenDem[array_rand($tenDem)];
            $tenNgauNhien = $ten[array_rand($ten)];

            // Kết hợp thành tên đầy đủ
            $hoVaTen = $hoNgauNhien . ' ' . $tenDemNgauNhien . ' ' . $tenNgauNhien;

            // Tạo giới tính ngẫu nhiên (true: nữ, false: nam)
            $gioiTinh = (bool)rand(0, 1);

            // Tạo số điện thoại Việt Nam đơn giản (10 chữ số)
            $sdt = '0' . rand(9,9) . rand(0,9) . rand(1000000, 9999999);

            User::factory()->create([
                'nhanvien_id' => NhanVien::factory()->create([
                    'phucap_id' => DB::table('phucap')->inRandomOrder()->first()->id,
                    'bangcap_id' => DB::table('bangcap')->inRandomOrder()->first()->id,
                    'chuyenmon_id' => DB::table('chuyenmon')->inRandomOrder()->first()->id,
                    'ngoaingu_id' => DB::table('ngoaingu')->inRandomOrder()->first()->id,
                    'dantoc_id' => DB::table('dantoc')->inRandomOrder()->first()->id,
                    'tongiao_id' => DB::table('tongiao')->inRandomOrder()->first()->id,
                    'hovaten' => $hoVaTen,
                    'sdt' => $sdt,
                    'gioitinh' => $gioiTinh
                ]),
                'email' => $faker->unique()->safeEmail,
                'role' => 0
            ]);
        }
    }
}
