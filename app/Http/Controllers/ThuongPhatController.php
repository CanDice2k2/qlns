<?php

namespace App\Http\Controllers;

use App\Models\ThuongPhat;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class ThuongPhatController extends Controller
{
    public function index()
    {
        return Inertia::render('ThuongPhat/Index', [
            'filters' => Request::all('search', 'trashed'),
            'thuongphat' => (new ThuongPhat())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($thuongphat) => [
                    'id' => $thuongphat->id,
                    'manv' => 'NV' . str_pad($thuongphat->nhanvien->id, 3, '0', STR_PAD_LEFT),
                    'hovaten' => $thuongphat->nhanvien->hovaten,
                    'loai' => $thuongphat->loai,
                    'sotien' => number_format($thuongphat->sotien) . ' VNĐ',
                    'ngayapdung' => str_pad($thuongphat->thang, 2, '0', STR_PAD_LEFT) .  '-' . $thuongphat->nam,
                    'deleted_at' => $thuongphat->deleted_at,
                ]),
        ]);
    }

    public function create(NhanVien $nhanvien)
    {
        return Inertia::render('ThuongPhat/Create', [
            'nhanvien' => [
                'id' => $nhanvien->id,
                'hovaten' => $nhanvien->hovaten
            ]
        ]);
    }

    public function store(NhanVien $nhanvien)
    {
        Request::validate([
            'loai' => ['required', 'boolean'],
            'lydo' => ['required', 'max:255'],
            'sotien' => ['required', 'integer'],
            'ngayapdung' => ['required', 'date'],
        ]);

        (new ThuongPhat())->create([
            'nhanvien_id' => $nhanvien->id,
            'loai' => Request::get('loai'),
            'lydo' => Request::get('lydo'),
            'sotien' => Request::get('sotien'),
            'thang' => intval(date('m', strtotime(Request::get('ngayapdung')))),
            'nam' => intval(date('Y', strtotime(Request::get('ngayapdung')))),
        ]);

        return Redirect::route('thuongphat')->with('success', 'Đã tạo thành công.');
    }

    public function edit(ThuongPhat $thuongphat)
    {
        return Inertia::render('ThuongPhat/Edit', [
            'thuongphat' => [
                'id' => $thuongphat->id,
                'hovaten' => $thuongphat->nhanvien->hovaten,
                'loai' => $thuongphat->loai,
                'lydo' => $thuongphat->lydo,
                'sotien' => $thuongphat->sotien,
                'ngayapdung' => $thuongphat->nam . '-' . str_pad($thuongphat->thang, 2, '0', STR_PAD_LEFT),
                'deleted_at' => $thuongphat->deleted_at,
            ],
        ]);
    }

    public function update(ThuongPhat $thuongphat)
    {
        Request::validate([
            'loai' => ['required', 'boolean'],
            'lydo' => ['required', 'max:255'],
            'sotien' => ['required', 'integer'],
            'ngayapdung' => ['required', 'date'],
        ]);

        $thuongphat->update([
            'loai' => Request::get('loai'),
            'lydo' => Request::get('lydo'),
            'sotien' => Request::get('sotien'),
            'thang' => date('m', strtotime(Request::get('ngayapdung'))),
            'nam' => date('Y', strtotime(Request::get('ngayapdung'))),
        ]);

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(ThuongPhat $thuongphat)
    {
        $thuongphat->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(ThuongPhat $thuongphat)
    {
        $thuongphat->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
