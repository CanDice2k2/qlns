<?php

namespace App\Http\Controllers;

use App\Models\BaoHiem;
use App\Models\KhauTru;
use App\Models\LoaiBaoHiem;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class BaoHiemController extends Controller
{
    public function index()
    {
        return Inertia::render('BaoHiem/Index', [
            'filters' => Request::all('search', 'trashed'),
            'baohiem' => (new BaoHiem())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($baohiem) => [
                    'id' => $baohiem->id,
                    'hovaten' => $baohiem->nhanvien->hovaten,
                    'loaibaohiem' => $baohiem->loaibaohiem->tenbh,
                    'maso' => $baohiem->maso,
                    'noicap' => $baohiem->noicap,
                    'ngaycap' => $baohiem->ngaycap,
                    'ngayhethan' => $baohiem->ngayhethan,
                    'mucdong' => $baohiem->mucdong,
                    'deleted_at' => $baohiem->deleted_at,
                ]),
        ]);
    }

    public function create(NhanVien $nhanvien)
    {
        return Inertia::render('BaoHiem/Create', [
            'loaibaohiem' => (new LoaiBaoHiem())
                ->orderBy('tenbh')
                ->get()
                ->map
                ->only('id', 'tenbh'),
            'nhanvien' => [
                'id' => $nhanvien->id,
                'hovaten' => $nhanvien->hovaten
            ]
        ]);
    }

    public function store(NhanVien $nhanvien)
    {
        Request::validate([
            'loaibaohiem' => ['required', Rule::exists('loaibaohiem', 'id'), Rule::unique('baohiem', 'loaibaohiem_id')->where('nhanvien_id', $nhanvien->id)],
            'maso' => ['required', 'max:100'],
            'noicap' => ['required', 'max:100'],
            'ngaycap' => ['required', 'date'],
            'ngayhethan' => ['required', 'date'],
            'mucdong' => ['required', 'between:0,100.00'],
            'khautru' => ['required', 'boolean']
        ]);

        (new BaoHiem())->create([
            'nhanvien_id' => $nhanvien->id,
            'loaibaohiem_id' => Request::get('loaibaohiem'),
            'maso' => Request::get('maso'),
            'noicap' => Request::get('noicap'),
            'ngaycap' => Request::get('ngaycap'),
            'ngayhethan' => Request::get('ngayhethan'),
            'mucdong' => Request::get('mucdong')
        ]);

        if (Request::get('khautru') == 1)
        {
            $thang = intval(date('m', time()));
            $nam = intval(date('Y', time()));
            $khautru = new KhauTru();
            if ($khautru->exists($nhanvien->id, Request::get('loaibaohiem'), $thang, $nam))
                return Redirect::back()->with('error', 'Khẩu trừ của bảo hiểm này đã tồn tại.');
            $khautru->create([
                'nhanvien_id' => $nhanvien->id,
                'loaibaohiem_id' => Request::get('loaibaohiem'),
                'mucdong' => Request::get('mucdong'),
                'thang' => $thang,
                'nam' => $nam,
            ]);
        }

        return Redirect::route('nhanvien.edit', $nhanvien->id)->with('success', 'Đã tạo thành công.');
    }

    public function edit(BaoHiem $baohiem)
    {
        return Inertia::render('BaoHiem/Edit', [
            'loaibaohiem' => (new LoaiBaoHiem())
                ->orderBy('tenbh')
                ->get()
                ->map
                ->only('id', 'tenbh'),
            'baohiem' => [
                'id' => $baohiem->id,
                'hovaten' => $baohiem->nhanvien->hovaten,
                'loaibaohiem' => $baohiem->loaibaohiem_id,
                'maso' => $baohiem->maso,
                'noicap' => $baohiem->noicap,
                'ngaycap' => $baohiem->ngaycap,
                'ngayhethan' => $baohiem->ngayhethan,
                'mucdong' => $baohiem->mucdong,
                'khautru' => 0,
                'deleted_at' => $baohiem->deleted_at,
            ],
        ]);
    }

    public function update(BaoHiem $baohiem)
    {
        Request::validate([
            'loaibaohiem' => ['required', Rule::exists('loaibaohiem', 'id'), Rule::unique('baohiem', 'loaibaohiem_id')->where('nhanvien_id', $baohiem->nhanvien->id)->ignore($baohiem->id)],
            'maso' => ['required', 'max:100'],
            'noicap' => ['required', 'max:100'],
            'ngaycap' => ['required', 'date'],
            'ngayhethan' => ['required', 'date'],
            'mucdong' => ['required', 'between:0,100.00'],
            'khautru' => ['required', 'boolean']
        ]);

        $baohiem->update([
            'loaibaohiem_id' => Request::get('loaibaohiem'),
            'maso' => Request::get('maso'),
            'noicap' => Request::get('noicap'),
            'ngaycap' => Request::get('ngaycap'),
            'ngayhethan' => Request::get('ngayhethan'),
            'mucdong' => Request::get('mucdong')
        ]);

        if (Request::get('khautru') == 1)
        {
            $thang = intval(date('m', time()));
            $nam = intval(date('Y', time()));
            $khautru = new KhauTru();

            if ($khautru->exists($baohiem->nhanvien->id, Request::get('loaibaohiem'), $thang, $nam))
                return Redirect::back()->with('error', 'Khẩu trừ của bảo hiểm này đã tồn tại.');

            $khautru->create([
                'nhanvien_id' => $baohiem->nhanvien->id,
                'loaibaohiem_id' => Request::get('loaibaohiem'),
                'mucdong' => Request::get('mucdong'),
                'thang' => $thang,
                'nam' => $nam,
            ]);
        }

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(BaoHiem $baohiem)
    {
        $baohiem->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(BaoHiem $baohiem)
    {
        $baohiem->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
