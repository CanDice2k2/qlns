<?php

namespace App\Imports;

use App\Models\NhanVien;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Str;

class NhanVienImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private function convertDateExcel($str)
    {
        return is_numeric($str) ? Date::excelToDateTimeObject($str)->format('Y-m-d') : $str;
    }

    public function collection(Collection $rows)
    {        
        foreach ($rows as $row) 
        {
            $nhanvien = (new NhanVien())->create([
                'phucap_id' => $row['phucap_id'],
                'bangcap_id' => $row['bangcap_id'],
                'chuyenmon_id' => $row['chuyenmon_id'],
                'ngoaingu_id' => $row['ngoaingu_id'],
                'dantoc_id' => $row['dantoc_id'],
                'tongiao_id' => $row['tongiao_id'],
                'hovaten' => $row['hovaten'],
                'gioitinh' => $row['gioitinh'],
                'ngaysinh' => $this->convertDateExcel($row['ngaysinh']),
                'cmnd' => $row['cmnd'],
                'sdt' => $row['sdt'],
                'diachi' => $row['diachi'],
                'quequan' => $row['quequan'],
                'trangthai' => $row['trangthai'],
                'ngaynghilam' => $this->convertDateExcel($row['ngaynghilam']),
                'bacluong' => $row['bacluong'],
                'hesoluong' => $row['hesoluong'],
                'photo_path' => $row['photo_path'],
            ]);
            (new User())->create([
                'nhanvien_id' => $nhanvien->id,
                'email' => $row['email'],
                'password' => $row['password'],
                'role' => $row['role'],
                'email_verified_at' => now(),
                'remember_token' => Str::random(10)
            ]);
        }
    }
}
