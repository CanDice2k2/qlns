<?php

namespace App\Http\Controllers;

use App\Models\KhauTru;
use App\Models\LoaiBaoHiem;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class KhauTruController extends Controller
{
    public function index()
    {
        return Inertia::render('KhauTru/Index', [
            'filters' => Request::all('search', 'trashed'),
            'khautru' => (new KhauTru())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($khautru) => [
                    'id' => $khautru->id,
                    'manv' => 'NV' . str_pad($khautru->nhanvien->id, 3, '0', STR_PAD_LEFT),
                    'hovaten' => $khautru->nhanvien->hovaten,
                    'loaibaohiem' => $khautru->loaibaohiem->tenbh,
                    'mucdong' => $khautru->mucdong,
                    'ngaydong' => str_pad($khautru->thang, 2, '0', STR_PAD_LEFT) . '-' . $khautru->nam,
                    'deleted_at' => $khautru->deleted_at,
                ]),
        ]);
    }

    public function edit(KhauTru $khautru)
    {
        return Inertia::render('KhauTru/Edit', [
            'loaibaohiem' => (new LoaiBaoHiem())
                ->orderBy('tenbh')
                ->get()
                ->map
                ->only('id', 'tenbh'),
            'khautru' => [
                'id' => $khautru->id,
                'hovaten' => $khautru->nhanvien->hovaten,
                'loaibaohiem' => $khautru->loaibaohiem_id,
                'mucdong' => $khautru->mucdong,
                'ngaydong' => $khautru->nam . '-' . str_pad($khautru->thang, 2, '0', STR_PAD_LEFT),
                'deleted_at' => $khautru->deleted_at,
            ],
        ]);
    }

    public function update(KhauTru $khautru)
    {
        Request::validate([
            'loaibaohiem' => ['required', Rule::exists('loaibaohiem', 'id')],
            'mucdong' => ['required', 'between:0,100.00'],
            'ngaydong' => ['required', 'date'],
        ]);

        $thang = date('m', strtotime(Request::get('ngaydong')));
        $nam = date('Y', strtotime(Request::get('ngaydong')));

        if ($khautru->exists($khautru->nhanvien->id, Request::get('loaibaohiem'), $thang, $nam, $khautru->id))
            return Redirect::back()->with('error', 'Khẩu trừ của bảo hiểm này đã tồn tại.');

        $khautru->update([
            'loaibaohiem_id' => Request::get('loaibaohiem'),
            'mucdong' => Request::get('mucdong'),
            'thang' => $thang,
            'nam' => $nam,
        ]);

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(KhauTru $khautru)
    {
        $khautru->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(KhauTru $khautru)
    {
        $khautru->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
