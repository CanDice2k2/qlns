<?php

namespace App\Http\Controllers;

use App\Models\NhanLuong;
use App\Models\NhanVien;
use App\Models\PhuCap;
use App\Models\ChucVu;
use App\Models\PhongBan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use App\Exports\NhanLuongExport;
use Inertia\Inertia;
use Excel;

class NhanLuongController extends Controller
{
    public function tinhLuong()
    {
        $nhanvien_id = Request::get('id');
        $thang = Request::get('thang');
        $nam = Request::get('nam');
        $ngaycong = Request::get('ngaycong');

        if (empty($nhanvien_id) || empty($thang) || empty($nam) || empty($ngaycong))
            return response()->json([]);

        if (!is_numeric($nhanvien_id) || !is_numeric($thang) || !is_numeric($nam) || !is_numeric($ngaycong))
            return response()->json([]);

        return response()->json((new NhanLuong())->tinhLuong($nhanvien_id, $ngaycong, $thang, $nam));
    }

    public function index()
    {
        return Inertia::render('NhanLuong/Index', [
            'filters' => Request::all('search', 'trashed', 'ngayluong'),
            'nhanluong' => (new NhanLuong())
                ->filter(Request::only('search', 'trashed', 'ngayluong'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($nhanluong) => [
                    'id' => $nhanluong->id,
                    'manv' => 'NV' . str_pad($nhanluong->nhanvien->id, 3, '0', STR_PAD_LEFT),
                    'hovaten' => $nhanluong->nhanvien->hovaten,
                    'thuclinh' => number_format($nhanluong->thuclinh) . ' VNĐ',
                    'ngaynhan' => str_pad($nhanluong->thang, 2, '0', STR_PAD_LEFT) . '-' . $nhanluong->nam,
                    'deleted_at' => $nhanluong->deleted_at,
                ]),
        ]);
    }

    public function create(NhanVien $nhanvien)
    {
        // Lấy thông tin chức vụ từ nhân viên
        $phuCap = PhuCap::find($nhanvien->phucap_id);
        $chucVu = $phuCap ? ChucVu::find($phuCap->chucvu_id) : null;
        $phongBan = $phuCap ? PhongBan::find($phuCap->phongban_id) : null;
        // dd($phuCap, $chucVu, $phongBan);
        return Inertia::render('NhanLuong/Create', [
            'nhanvien' => [
                'id' => $nhanvien->id,
                'hovaten' => $nhanvien->hovaten,
                'chucvu' => $chucVu ? $chucVu->tencv : 'Không xác định',
                'phongban' => $phongBan ? $phongBan->tenpb : 'Không xác định',
            ]
        ]);
    }

    public function store(NhanVien $nhanvien)
    {
        Request::validate([
            'heso' => ['required', 'between:0,100.00'],
            'hsphucap' => ['required', 'between:0,100.00'],
            'khautru' => ['required', 'integer'],
            'luongcb' => ['required', 'integer'],
            'mucluong' => ['required', 'integer'],
            'phucap' => ['required', 'integer'],
            'ngaycongchuan' => ['required', 'integer'],
            'ngaycong' => ['required', 'integer'],
            'nghihl' => ['required', 'integer'],
            'nghikhl' => ['required', 'integer'],
            'thuong' => ['required', 'integer'],
            'phat' => ['required', 'integer'],
            'tamung' => ['required', 'integer'],
            'thuclinh' => ['required', 'integer'],
            'ngaynhan' => ['required', 'date'],
        ]);

        (new NhanLuong())->create([
            'nhanvien_id' => $nhanvien->id,
            'heso' => Request::get('heso'),
            'hsphucap' => Request::get('hsphucap'),
            'khautru' => Request::get('khautru'),
            'luongcb' => Request::get('luongcb'),
            'mucluong' => Request::get('mucluong'),
            'phucap' => Request::get('phucap'),
            'ngaycongchuan' => Request::get('ngaycongchuan'),
            'ngaycong' => Request::get('ngaycong'),
            'nghihl' => Request::get('nghihl'),
            'nghikhl' => Request::get('nghikhl'),
            'thuong' => Request::get('thuong'),
            'phat' => Request::get('phat'),
            'tamung' => Request::get('tamung'),
            'thuclinh' => Request::get('thuclinh'),
            'thang' => date('m', strtotime(Request::get('ngaynhan'))),
            'nam' => date('Y', strtotime(Request::get('ngaynhan'))),
        ]);

        return Redirect::route('nhanluong')->with('success', 'Đã tạo thành công.');
    }

    public function edit(NhanLuong $nhanluong)
    {
        // Lấy thông tin chức vụ từ nhân viên
        $nhanVien = $nhanluong->nhanvien;

        // Cách sửa lỗi:
        $phuCap = PhuCap::find($nhanVien->phucap_id);
        $chucVu = $phuCap ? ChucVu::find($phuCap->chucvu_id) : null;
        $phongBan = $phuCap ? PhongBan::find($phuCap->phongban_id) : null;

        // Kiểm tra và tính toán chi tiết khấu trừ
        $khauTruDetails = [
            'bhxh' => $nhanluong->mucluong > 0 ? round($nhanluong->mucluong * 0.08) : 0,
            'bhyt' => $nhanluong->mucluong > 0 ? round($nhanluong->mucluong * 0.015) : 0,
            'bhtn' => $nhanluong->mucluong > 0 ? round($nhanluong->mucluong * 0.01) : 0,
            'thueTNCN' => $nhanluong->khautru > 0 ?
                max(0, $nhanluong->khautru - ($nhanluong->mucluong > 0 ? round($nhanluong->mucluong * 0.105) : 0)) : 0
        ];

        // Xóa dd() để cho phép hiển thị view
        // dd($khauTruDetails);

        return Inertia::render('NhanLuong/Edit', [
            'nhanluong' => [
                'id' => $nhanluong->id,
                'hovaten' => $nhanluong->nhanvien->hovaten,
                'heso' => $nhanluong->heso,
                'hsphucap' => $nhanluong->hsphucap,
                'khautru' => $nhanluong->khautru,
                'luongcb' => $nhanluong->luongcb,
                'mucluong' => $nhanluong->mucluong,
                'phucap' => $nhanluong->phucap,
                'ngaycongchuan' => $nhanluong->ngaycongchuan,
                'ngaycong' => $nhanluong->ngaycong,
                'nghihl' => $nhanluong->nghihl,
                'nghikhl' => $nhanluong->nghikhl,
                'thuong' => $nhanluong->thuong,
                'phat' => $nhanluong->phat,
                'tamung' => $nhanluong->tamung,
                'thuclinh' => $nhanluong->thuclinh,
                'ngaynhan' => $nhanluong->nam . '-' . str_pad($nhanluong->thang, 2, '0', STR_PAD_LEFT),
                'deleted_at' => $nhanluong->deleted_at,

                // Bổ sung thông tin chi tiết
                'chucvu' => $chucVu ? $chucVu->tencv : 'Không xác định',
                'phongban' => $phongBan ? $phongBan->tenpb : 'Không xác định',
                'khautru_details' => $khauTruDetails
            ],
        ]);
    }

    public function update(NhanLuong $nhanluong)
    {
        Request::validate([
            'heso' => ['required', 'between:0,100.00'],
            'hsphucap' => ['required', 'between:0,100.00'],
            'khautru' => ['required', 'integer'],
            'luongcb' => ['required', 'integer'],
            'mucluong' => ['required', 'integer'],
            'phucap' => ['required', 'integer'],
            'ngaycongchuan' => ['required', 'integer'],
            'ngaycong' => ['required', 'integer'],
            'nghihl' => ['required', 'integer'],
            'nghikhl' => ['required', 'integer'],
            'thuong' => ['required', 'integer'],
            'phat' => ['required', 'integer'],
            'tamung' => ['required', 'integer'],
            'thuclinh' => ['required', 'integer'],
            'ngaynhan' => ['required', 'date'],
        ]);

        $nhanluong->update([
            'heso' => Request::get('heso'),
            'hsphucap' => Request::get('hsphucap'),
            'khautru' => Request::get('khautru'),
            'luongcb' => Request::get('luongcb'),
            'mucluong' => Request::get('mucluong'),
            'phucap' => Request::get('phucap'),
            'ngaycongchuan' => Request::get('ngaycongchuan'),
            'ngaycong' => Request::get('ngaycong'),
            'nghihl' => Request::get('nghihl'),
            'nghikhl' => Request::get('nghikhl'),
            'thuong' => Request::get('thuong'),
            'phat' => Request::get('phat'),
            'tamung' => Request::get('tamung'),
            'thuclinh' => Request::get('thuclinh'),
            'thang' => date('m', strtotime(Request::get('ngaynhan'))),
            'nam' => date('Y', strtotime(Request::get('ngaynhan'))),
        ]);

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(NhanLuong $nhanluong)
    {
        $nhanluong->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(NhanLuong $nhanluong)
    {
        $nhanluong->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }

    public function export()
    {
        return Excel::download(new NhanLuongExport(Request::get('ngayluong')), 'danh-sach-nhan-luong.xlsx');
    }
}
