<?php

namespace App\Exports;

use App\Models\NhanLuong;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;

class NhanLuongExport implements FromCollection,
                                WithHeadings,
                                WithCustomStartCell,
                                WithMapping
    {
    /**
    * @return \Illuminate\Support\Collection
    */

    private $ngaynhan;

    public function __construct(string $ngaynhan = null)
    {
        $this->ngaynhan = $ngaynhan;
    }

    public function headings(): array
    {
        return [
            'hovaten',
            'heso',
            'hsphucap',
            'khautru',
            'luongcb',
            'mucluong',
            'phucap',
            'ngaycongchuan',
            'ngaycong',
            'nghihl',
            'nghikhl',
            'thuong',
            'phat',
            'tamung',
            'thuclinh',
            'thang',
            'nam',
        ];
    }

    public function map($row): array
    {
        return [
            $row->hovaten,
            !empty($row->heso) ? $row->heso : '0',
            !empty($row->hsphucap) ? $row->hsphucap : '0',
            !empty($row->khautru) ? $row->khautru : '0',
            !empty($row->luongcb) ? $row->luongcb : '0',
            !empty($row->mucluong) ? $row->mucluong : '0',
            !empty($row->phucap) ? $row->phucap : '0',
            !empty($row->ngaycongchuan) ? $row->ngaycongchuan : '0',
            !empty($row->ngaycong) ? $row->ngaycong : '0',
            !empty($row->nghihl) ? $row->nghihl : '0',
            !empty($row->nghikhl) ? $row->nghikhl : '0',
            !empty($row->thuong) ? $row->thuong : '0',
            !empty($row->phat) ? $row->phat : '0',
            !empty($row->tamung) ? $row->tamung : '0',
            !empty($row->thuclinh) ? $row->thuclinh : '0',
            $row->thang,
            $row->nam,
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function collection()
    {
        if (!empty($this->ngaynhan))
        {
            $this->ngaynhan = explode('-', $this->ngaynhan);
            if (!empty($this->ngaynhan[0]) && !empty($this->ngaynhan[1]))
            {
                return (new NhanLuong())
                ->join('nhanvien as nv', 'nhanluong.nhanvien_id', '=', 'nv.id')
                ->where('thang', $this->ngaynhan[1])
                ->where('nam', $this->ngaynhan[0])
                ->get();
            }
        }
        return (new NhanLuong())
                ->join('nhanvien as nv', 'nhanluong.nhanvien_id', '=', 'nv.id')
                ->get();
    }
}
