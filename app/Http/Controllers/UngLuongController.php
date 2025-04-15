<?php

namespace App\Http\Controllers;

use App\Models\UngLuong;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class UngLuongController extends Controller
{
    public function index()
    {
        return Inertia::render('UngLuong/Index', [
            'filters' => Request::all('search', 'trashed'),
            'ungluong' => (new UngLuong())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($ungluong) => [
                    'id' => $ungluong->id,
                    'manv' => 'NV' . str_pad($ungluong->nhanvien->id, 3, '0', STR_PAD_LEFT),
                    'hovaten' => $ungluong->nhanvien->hovaten,
                    'sotien' => number_format($ungluong->sotien) . ' VNĐ',
                    'ngayung' => str_pad($ungluong->thang, 2, '0', STR_PAD_LEFT) . '-' . $ungluong->nam,
                    'deleted_at' => $ungluong->deleted_at,
                ]),
        ]);
    }

    public function create(NhanVien $nhanvien)
    {
        return Inertia::render('UngLuong/Create', [
            'nhanvien' => [
                'id' => $nhanvien->id,
                'hovaten' => $nhanvien->hovaten
            ]
        ]);
    }

    public function store(NhanVien $nhanvien)
    {
        Request::validate([
            'lydo' => ['required', 'max:255'],
            'sotien' => ['required', 'integer'],
            'ngayung' => ['required', 'date'],
        ]);

        (new UngLuong())->create([
            'nhanvien_id' => $nhanvien->id,
            'lydo' => Request::get('lydo'),
            'sotien' => Request::get('sotien'),
            'thang' => date('m', strtotime(Request::get('ngayung'))),
            'nam' => date('Y', strtotime(Request::get('ngayung'))),
        ]);

        return Redirect::route('ungluong')->with('success', 'Đã tạo thành công.');
    }

    public function edit(UngLuong $ungluong)
    {
        return Inertia::render('UngLuong/Edit', [
            'ungluong' => [
                'id' => $ungluong->id,
                'hovaten' => $ungluong->nhanvien->hovaten,
                'lydo' => $ungluong->lydo,
                'sotien' => $ungluong->sotien,
                'ngayung' => $ungluong->nam . '-' . str_pad($ungluong->thang, 2, '0', STR_PAD_LEFT),
                'deleted_at' => $ungluong->deleted_at,
            ],
        ]);
    }

    public function update(UngLuong $ungluong)
    {
        Request::validate([
            'lydo' => ['required', 'max:255'],
            'sotien' => ['required', 'integer'],
            'ngayung' => ['required', 'date'],
        ]);

        $ungluong->update([
            'lydo' => Request::get('lydo'),
            'sotien' => Request::get('sotien'),
            'thang' => date('m', strtotime(Request::get('ngayung'))),
            'nam' => date('Y', strtotime(Request::get('ngayung'))),
        ]);

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(UngLuong $ungluong)
    {
        $ungluong->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(UngLuong $ungluong)
    {
        $ungluong->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
