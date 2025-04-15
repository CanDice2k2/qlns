<?php

namespace App\Exports;

use App\Models\NhanVien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;

class NhanVienExport implements FromCollection,
                                WithHeadings,
                                WithCustomStartCell,
                                WithMapping
    {
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'phucap_id',
            'bangcap_id',
            'chuyenmon_id',
            'ngoaingu_id',
            'dantoc_id',
            'tongiao_id',
            'hovaten',
            'gioitinh',
            'ngaysinh',
            'cmnd',
            'email',
            'password',
            'role',
            'sdt',
            'diachi',
            'quequan',
            'trangthai',
            'ngaynghilam',
            'bacluong',
            'hesoluong',
            'photo_path',
        ];
    }

    public function map($row): array
    {
        return [
            $row->phucap_id,
            $row->bangcap_id,
            $row->chuyenmon_id,
            $row->ngoaingu_id,
            $row->dantoc_id,
            $row->tongiao_id,
            $row->hovaten,
            (!empty($row->gioitinh) ? $row->gioitinh : '0'),
            $row->ngaysinh,
            $row->cmnd,
            $row->email,
            $row->password,
            (!empty($row->role) ? $row->role : '0'),
            $row->sdt,
            $row->diachi,
            $row->quequan,
            $row->trangthai,
            $row->ngaynghilam,
            $row->bacluong,
            $row->hesoluong,
            $row->photo_path,
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function collection()
    {
        return (new NhanVien())->join('users as u', 'nhanvien.id', '=', 'u.nhanvien_id')->get();
    }
}
