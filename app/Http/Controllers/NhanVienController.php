<?php

namespace App\Http\Controllers;

use App\Models\BangCap;
use App\Models\BaoHiem;
use App\Models\ChuyenMon;
use App\Models\DanToc;
use App\Models\HopDong;
use App\Models\NhanVien;
use App\Models\PhuCap;
use App\Models\NgoaiNgu;
use App\Models\TonGiao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use App\Imports\NhanVienImport;
use App\Exports\NhanVienExport;
use Excel;

class NhanVienController extends Controller
{
    public function index()
    {
        return Inertia::render('NhanVien/Index', [
            'filters' => Request::all('search', 'trashed', 'gioitinh', 'trangthai'),
            'nhanvien' => Auth::user()->nhanvien
                ->latest('nhanvien.created_at')
                ->filter(Request::only('search', 'trashed', 'gioitinh', 'trangthai'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($nhanvien) => [
                    'id' => $nhanvien->id,
                    'manv' => 'NV' . str_pad($nhanvien->id, 3, '0', STR_PAD_LEFT),
                    'hovaten' => $nhanvien->hovaten,
                    'email' => $nhanvien->user->email,
                    'sdt' => $nhanvien->sdt,
                    'gioitinh' => $nhanvien->gioitinh,
                    'trangthai' => $nhanvien->trangthai,
                    'photo' => $nhanvien->photo_path ? URL::route('image', ['path' => $nhanvien->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $nhanvien->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('NhanVien/Create', [
            'phucap' => (new PhuCap())->getAll(),
            'bangcap' => (new BangCap())
                ->orderBy('tenbc')
                ->get()
                ->map
                ->only('id', 'tenbc'),
            'chuyenmon' => (new ChuyenMon())
                ->orderBy('tencm')
                ->get()
                ->map
                ->only('id', 'tencm'),
            'ngoaingu' => (new NgoaiNgu())
                ->orderBy('tenng')
                ->get()
                ->map
                ->only('id', 'tenng'),
            'dantoc' => (new DanToc())
                ->orderBy('tendt')
                ->get()
                ->map
                ->only('id', 'tendt'),
            'tongiao' => (new TonGiao())
                ->orderBy('tentg')
                ->get()
                ->map
                ->only('id', 'tentg')
        ]);
    }

    public function store()
    {
        Request::validate([
            'phucap' => ['required', Rule::exists('phucap', 'id')],
            'bangcap' => ['required', Rule::exists('bangcap', 'id')],
            'chuyenmon' => ['required', Rule::exists('chuyenmon', 'id')],
            'ngoaingu' => ['required', Rule::exists('ngoaingu', 'id')],
            'dantoc' => ['required', Rule::exists('dantoc', 'id')],
            'tongiao' => ['required', Rule::exists('tongiao', 'id')],
            'hovaten' => ['required', 'max:100'],
            'gioitinh' => ['required', 'boolean'],
            'ngaysinh' => ['required', 'date'],
            'role' => ['required', 'between:0,2'],
            'email' => ['required', 'max:100', 'email', Rule::unique('users', 'email')],
            'password' => ['required'],
            'sdt' => ['required', 'max:15'],
            'cmnd' => ['required', 'max:50'],
            'diachi' => ['nullable', 'max:255'],
            'quequan' => ['nullable', 'max:255'],
            'trangthai' => ['required', 'boolean'],
            'bacluong' => ['required', 'between:1,10'],
            'hesoluong' => ['required', 'between:0,100.00'],
            'photo' => ['nullable', 'image']
        ]);

        Auth::user()->nhanvien->user->create([
            'nhanvien_id' => Auth::user()->nhanvien->create([
                'phucap_id' => Request::get('phucap'),
                'bangcap_id' => Request::get('bangcap'),
                'chuyenmon_id' => Request::get('chuyenmon'),
                'ngoaingu_id' => Request::get('ngoaingu'),
                'dantoc_id' => Request::get('dantoc'),
                'tongiao_id' => Request::get('tongiao'),
                'hovaten' => Request::get('hovaten'),
                'gioitinh' => Request::get('gioitinh'),
                'ngaysinh' => Request::get('ngaysinh'),
                'sdt' => Request::get('sdt'),
                'cmnd' => Request::get('cmnd'),
                'diachi' => Request::get('diachi'),
                'quequan' => Request::get('quequan'),
                'trangthai' => Request::get('trangthai'),
                'bacluong' => Request::get('bacluong'),
                'hesoluong' => Request::get('hesoluong'),
                'photo_path' => Request::file('photo') ? Request::file('photo')->store('nhanvien') : null,
            ])->id,
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'role' => Request::get('role')
        ]);

        return Redirect::route('nhanvien')->with('success', 'Đã tạo thành công.');
    }

    public function edit(NhanVien $nhanvien)
    {
        return Inertia::render('NhanVien/Edit', [
            'phucap' => (new PhuCap())->getAll(),
            'bangcap' => (new BangCap())
                ->orderBy('tenbc')
                ->get()
                ->map
                ->only('id', 'tenbc'),
            'chuyenmon' => (new ChuyenMon())
                ->orderBy('tencm')
                ->get()
                ->map
                ->only('id', 'tencm'),
            'ngoaingu' => (new NgoaiNgu())
                ->orderBy('tenng')
                ->get()
                ->map
                ->only('id', 'tenng'),
            'dantoc' => (new DanToc())
                ->orderBy('tendt')
                ->get()
                ->map
                ->only('id', 'tendt'),
            'tongiao' => (new TonGiao())
                ->orderBy('tentg')
                ->get()
                ->map
                ->only('id', 'tentg'),
            'nhanvien' => [
                'id' => $nhanvien->id,
                'user_id' => $nhanvien->user->id,
                'phucap' => $nhanvien->phucap_id,
                'bangcap' => $nhanvien->bangcap_id ?? null,
                'chuyenmon' => $nhanvien->chuyenmon_id,
                'ngoaingu' => $nhanvien->ngoaingu_id,
                'dantoc' => $nhanvien->dantoc_id,
                'tongiao' => $nhanvien->tongiao_id,
                'hovaten' => $nhanvien->hovaten,
                'gioitinh' => $nhanvien->gioitinh,
                'ngaysinh' => $nhanvien->ngaysinh,
                'sdt' => $nhanvien->sdt,
                'cmnd' => $nhanvien->cmnd,
                'diachi' => $nhanvien->diachi,
                'quequan' => $nhanvien->quequan,
                'trangthai' => $nhanvien->trangthai,
                'bacluong' => $nhanvien->bacluong,
                'hesoluong' => $nhanvien->hesoluong,
                'photo' => $nhanvien->photo_path ? URL::route('image', ['path' => $nhanvien->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $nhanvien->deleted_at,
            ],
            'baohiem' => (new BaoHiem())->where('nhanvien_id', $nhanvien->id)->get()->transform(fn ($baohiem) => [
                'id' => $baohiem->id,
                'maso' => $baohiem->maso,
                'tenbh' => $baohiem->loaibaohiem->tenbh,
                'ngaycap' => $baohiem->ngaycap,
                'ngayhethan' => $baohiem->ngayhethan,
                'mucdong' => $baohiem->mucdong
            ]),
            'hopdong' => (new HopDong())->where('nhanvien_id', $nhanvien->id)->get()->transform(fn ($hopdong) => [
                'id' => $hopdong->id,
                'mahd' => 'HD' . str_pad($hopdong->id, 3, '0', STR_PAD_LEFT),
                'ngaybd' => $hopdong->ngaybd,
                'ngaykt' => $hopdong->ngaykt ?? 'Vô thời hạn',
                'loaihopdong' => $hopdong->loaihopdong
            ])
        ]);
    }

    public function update(NhanVien $nhanvien)
    {
        Request::validate([
            'phucap' => ['required', Rule::exists('phucap', 'id')],
            'bangcap' => ['required', Rule::exists('bangcap', 'id')],
            'chuyenmon' => ['required', Rule::exists('chuyenmon', 'id')],
            'ngoaingu' => ['required', Rule::exists('ngoaingu', 'id')],
            'dantoc' => ['required', Rule::exists('dantoc', 'id')],
            'tongiao' => ['required', Rule::exists('tongiao', 'id')],
            'hovaten' => ['required', 'max:100'],
            'gioitinh' => ['required', 'boolean'],
            'ngaysinh' => ['required', 'date'],
            'sdt' => ['required', 'max:15'],
            'cmnd' => ['required', 'max:50'],
            'diachi' => ['nullable', 'max:255'],
            'quequan' => ['nullable', 'max:255'],
            'trangthai' => ['required', 'boolean'],
            'bacluong' => ['required', 'between:1,10'],
            'hesoluong' => ['required', 'between:0,100.00'],
            'photo' => ['nullable', 'image']
        ]);

        $nhanvien->update([
            'phucap_id' => Request::get('phucap'),
            'bangcap_id' => Request::get('bangcap'),
            'chuyenmon_id' => Request::get('chuyenmon'),
            'ngoaingu_id' => Request::get('ngoaingu'),
            'dantoc_id' => Request::get('dantoc'),
            'tongiao_id' => Request::get('tongiao'),
            'hovaten' => Request::get('hovaten'),
            'gioitinh' => Request::get('gioitinh'),
            'ngaysinh' => Request::get('ngaysinh'),
            'sdt' => Request::get('sdt'),
            'cmnd' => Request::get('cmnd'),
            'diachi' => Request::get('diachi'),
            'quequan' => Request::get('quequan'),
            'trangthai' => Request::get('trangthai'),
            'bacluong' => Request::get('bacluong'),
            'hesoluong' => Request::get('hesoluong')
        ]);

        if (Request::file('photo')) {
            $nhanvien->update(['photo_path' => Request::file('photo')->store('users')]);
        }

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(NhanVien $nhanvien)
    {
        $nhanvien->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(NhanVien $nhanvien)
    {
        $nhanvien->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }

    public function import()
    {
        Excel::import(new NhanVienImport, Request::file('file_import'));
        return redirect()->route('nhanvien');
    }

    public function export()
    {
        return Excel::download(new NhanVienExport, 'danh-sach-nhan-vien.xlsx');
    }
}
