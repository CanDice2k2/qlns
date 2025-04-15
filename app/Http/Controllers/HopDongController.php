<?php

namespace App\Http\Controllers;

use App\Models\HopDong;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class HopDongController extends Controller
{
    public function index()
    {
        return Inertia::render('HopDong/Index', [
            'filters' => Request::all('search', 'trashed'),
            'hopdong' => (new HopDong())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($hopdong) => [
                    'id' => $hopdong->id,
                    'mahd' => 'HD' . str_pad($hopdong->id, 3, '0', STR_PAD_LEFT),
                    'hovaten' => $hopdong->nhanvien->hovaten,
                    'ngaybd' => $hopdong->ngaybd,
                    'ngaykt' => $hopdong->ngaykt ?? 'Vô thời hạn',
                    'loaihopdong' => $hopdong->loaihopdong,
                    'deleted_at' => $hopdong->deleted_at,
                ]),
        ]);
    }

    public function create(NhanVien $nhanvien)
    {
        return Inertia::render('HopDong/Create', [
            'nhanvien' => [
                'id' => $nhanvien->id,
                'hovaten' => $nhanvien->hovaten
            ]
        ]);
    }

    public function store(NhanVien $nhanvien)
    {
        Request::validate([
            'loaihopdong' => ['required', 'boolean'],
            'ngaybd' => ['required', 'date'],
            'ngaykt' => ['nullable', 'date'],
        ]);

        (new HopDong())->create([
            'nhanvien_id' => $nhanvien->id,
            'loaihopdong' => Request::get('loaihopdong'),
            'ngaybd' => Request::get('ngaybd'),
            'ngaykt' => Request::get('ngaykt'),
        ]);

        return Redirect::route('nhanvien.edit', $nhanvien->id)->with('success', 'Đã tạo thành công.');
    }

    public function edit(HopDong $hopdong)
    {
        return Inertia::render('HopDong/Edit', [
            'hopdong' => [
                'id' => $hopdong->id,
                'hovaten' => $hopdong->nhanvien->hovaten,
                'ngaybd' => $hopdong->ngaybd,
                'ngaykt' => $hopdong->ngaykt,
                'loaihopdong' => $hopdong->loaihopdong,
                'deleted_at' => $hopdong->deleted_at,
            ],
        ]);
    }

    public function update(HopDong $hopdong)
    {
        $hopdong->update(
            Request::validate([
                'loaihopdong' => ['required', 'boolean'],
                'ngaybd' => ['required', 'date'],
                'ngaykt' => ['nullable', 'date'],
            ])
        );

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(HopDong $hopdong)
    {
        $hopdong->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(HopDong $hopdong)
    {
        $hopdong->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
