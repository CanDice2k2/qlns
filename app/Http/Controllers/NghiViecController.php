<?php

namespace App\Http\Controllers;

use App\Models\NghiViec;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class NghiViecController extends Controller
{
    public function index()
    {
        return Inertia::render('NghiViec/Index', [
            'filters' => Request::all('search', 'trashed'),
            'nghiviec' => (new NghiViec())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($nghiviec) => [
                    'id' => $nghiviec->id,
                    'manv' => 'NV' . str_pad($nghiviec->nhanvien->id, 3, '0', STR_PAD_LEFT),
                    'hovaten' => $nghiviec->nhanvien->hovaten,
                    'ngaybd' => $nghiviec->ngaybd,
                    'ngaykt' => $nghiviec->ngaykt,
                    'huongluong' => $nghiviec->huongluong,
                    'deleted_at' => $nghiviec->deleted_at,
                ]),
        ]);
    }

    public function create(NhanVien $nhanvien)
    {
        return Inertia::render('NghiViec/Create', [
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
            'ngaybd' => ['required', 'date'],
            'ngaykt' => ['required', 'date'],
            'huongluong' => ['required', 'boolean']
        ]);
        $nghiviec = new NghiViec();

        if (!$nghiviec->checkDateStartEnd(Request::get('ngaybd'), Request::get('ngaykt')))
            return Redirect::back()->with('error', 'Ngày bắt đầu phải nhỏ hơn bằng ngày kết thúc.');

        if ($nghiviec->checkNgayCong($nhanvien->id, Request::get('ngaybd'), Request::get('ngaykt')))
            return Redirect::back()->with('error', 'Ngày nghĩ đã được chấm công.');

        if ($nghiviec->exists($nhanvien->id, Request::get('ngaybd'), Request::get('ngaykt')))
            return Redirect::back()->with('error', 'Ngày nghỉ đã bị trùng.');

        $nghiviec->create([
            'nhanvien_id' => $nhanvien->id,
            'lydo' => Request::get('lydo'),
            'ngaybd' => Request::get('ngaybd'),
            'ngaykt' => Request::get('ngaykt'),
            'huongluong' => Request::get('huongluong')
        ]);

        return Redirect::route('nghiviec')->with('success', 'Đã tạo thành công.');
    }

    public function edit(NghiViec $nghiviec)
    {
        return Inertia::render('NghiViec/Edit', [
            'nghiviec' => [
                'id' => $nghiviec->id,
                'hovaten' => $nghiviec->nhanvien->hovaten,
                'lydo' => $nghiviec->lydo,
                'ngaybd' => $nghiviec->ngaybd,
                'ngaykt' => $nghiviec->ngaykt,
                'huongluong' => $nghiviec->huongluong,
                'deleted_at' => $nghiviec->deleted_at,
            ],
        ]);
    }

    public function update(NghiViec $nghiviec)
    {
        Request::validate([
            'lydo' => ['required', 'max:255'],
            'ngaybd' => ['required', 'date'],
            'ngaykt' => ['required', 'date'],
            'huongluong' => ['required', 'boolean']
        ]);

        if (!$nghiviec->checkDateStartEnd(Request::get('ngaybd'), Request::get('ngaykt')))
            return Redirect::back()->with('error', 'Ngày bắt đầu phải nhỏ hơn bằng ngày kết thúc.');

        if ($nghiviec->checkNgayCong($nghiviec->nhanvien->id, Request::get('ngaybd'), Request::get('ngaykt')))
            return Redirect::back()->with('error', 'Ngày nghĩ đã được chấm công.');

        if ($nghiviec->exists($nghiviec->nhanvien->id, Request::get('ngaybd'), Request::get('ngaykt'), $nghiviec->id))
            return Redirect::back()->with('error', 'Ngày nghỉ đã bị trùng.');

        $nghiviec->update([
            'lydo' => Request::get('lydo'),
            'ngaybd' => Request::get('ngaybd'),
            'ngaykt' => Request::get('ngaykt'),
            'huongluong' => Request::get('huongluong')
        ]);

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(NghiViec $nghiviec)
    {
        $nghiviec->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(NghiViec $nghiviec)
    {
        $nghiviec->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
