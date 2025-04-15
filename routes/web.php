<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\ChamCongController;
use App\Http\Controllers\BangCapController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\ChuyenMonController;
use App\Http\Controllers\DanTocController;
use App\Http\Controllers\LoaiBaoHiemController;
use App\Http\Controllers\NgoaiNguController;
use App\Http\Controllers\PhongBanController;
use App\Http\Controllers\TonGiaoController;
use App\Http\Controllers\PhuCapController;
use App\Http\Controllers\BaoHiemController;
use App\Http\Controllers\HopDongController;
use App\Http\Controllers\UngLuongController;
use App\Http\Controllers\NghiViecController;
use App\Http\Controllers\ThuongPhatController;
use App\Http\Controllers\HeSoController;
use App\Http\Controllers\BangChamCongController;
use App\Http\Controllers\NhanLuongController;
use App\Http\Controllers\KhauTruController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// NhanVien

Route::get('nhanvien', [NhanVienController::class, 'index'])
    ->name('nhanvien')
    ->middleware('auth');

Route::get('nhanvien/create', [NhanVienController::class, 'create'])
    ->name('nhanvien.create')
    ->middleware('auth');

Route::post('nhanvien', [NhanVienController::class, 'store'])
    ->name('nhanvien.store')
    ->middleware('auth');

Route::get('nhanvien/{nhanvien}/edit', [NhanVienController::class, 'edit'])
    ->name('nhanvien.edit')
    ->middleware('auth');

Route::put('nhanvien/{nhanvien}', [NhanVienController::class, 'update'])
    ->name('nhanvien.update')
    ->middleware('auth');

Route::delete('nhanvien/{nhanvien}', [NhanVienController::class, 'destroy'])
    ->name('nhanvien.destroy')
    ->middleware('auth');

Route::put('nhanvien/{nhanvien}/restore', [NhanVienController::class, 'restore'])
    ->name('nhanvien.restore')
    ->middleware('auth');

Route::post('nhanvien/import', [NhanVienController::class, 'import'])
    ->name('nhanvien.import')
    ->middleware('auth');

Route::get('nhanvien/export', [NhanVienController::class, 'export'])
    ->name('nhanvien.export')
    ->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');

// ChamCong
Route::get('chamcong', [ChamCongController::class, 'index'])
    ->name('chamcong')
    ->middleware('auth');

Route::get('chamcong/{nhanvien}/create', [ChamCongController::class, 'create'])
    ->name('chamcong.create')
    ->middleware('auth');

Route::get('chamcong/{chamcong}/edit', [ChamCongController::class, 'edit'])
    ->name('chamcong.edit')
    ->middleware('auth');

Route::post('chamcong/{nhanvien}', [ChamCongController::class, 'store'])
    ->name('chamcong.store')
    ->middleware('auth');

Route::put('chamcong/{chamcong}', [ChamCongController::class, 'update'])
    ->name('chamcong.update')
    ->middleware('auth');

Route::delete('chamcong/{chamcong}', [ChamCongController::class, 'destroy'])
    ->name('chamcong.destroy')
    ->middleware('auth');

Route::put('chamcong/{chamcong}/restore', [ChamCongController::class, 'restore'])
    ->name('chamcong.restore')
    ->middleware('auth');


// BangCap

Route::get('bangcap', [BangCapController::class, 'index'])
    ->name('bangcap')
    ->middleware('auth');

Route::get('bangcap/create', [BangCapController::class, 'create'])
    ->name('bangcap.create')
    ->middleware('auth');

Route::post('bangcap', [BangCapController::class, 'store'])
    ->name('bangcap.store')
    ->middleware('auth');

Route::get('bangcap/{bangcap}/edit', [BangCapController::class, 'edit'])
    ->name('bangcap.edit')
    ->middleware('auth');

Route::put('bangcap/{bangcap}', [BangCapController::class, 'update'])
    ->name('bangcap.update')
    ->middleware('auth');

Route::delete('bangcap/{bangcap}', [BangCapController::class, 'destroy'])
    ->name('bangcap.destroy')
    ->middleware('auth');

Route::put('bangcap/{bangcap}/restore', [BangCapController::class, 'restore'])
    ->name('bangcap.restore')
    ->middleware('auth');

// ChucVu

Route::get('chucvu', [ChucVuController::class, 'index'])
    ->name('chucvu')
    ->middleware('auth');

Route::get('chucvu/create', [ChucVuController::class, 'create'])
    ->name('chucvu.create')
    ->middleware('auth');

Route::post('chucvu', [ChucVuController::class, 'store'])
    ->name('chucvu.store')
    ->middleware('auth');

Route::get('chucvu/{chucvu}/edit', [ChucVuController::class, 'edit'])
    ->name('chucvu.edit')
    ->middleware('auth');

Route::put('chucvu/{chucvu}', [ChucVuController::class, 'update'])
    ->name('chucvu.update')
    ->middleware('auth');

Route::delete('chucvu/{chucvu}', [ChucVuController::class, 'destroy'])
    ->name('chucvu.destroy')
    ->middleware('auth');

Route::put('chucvu/{chucvu}/restore', [ChucVuController::class, 'restore'])
    ->name('chucvu.restore')
    ->middleware('auth');

// ChuyenMon

Route::get('chuyenmon', [ChuyenMonController::class, 'index'])
    ->name('chuyenmon')
    ->middleware('auth');

Route::get('chuyenmon/create', [ChuyenMonController::class, 'create'])
    ->name('chuyenmon.create')
    ->middleware('auth');

Route::post('chuyenmon', [ChuyenMonController::class, 'store'])
    ->name('chuyenmon.store')
    ->middleware('auth');

Route::get('chuyenmon/{chuyenmon}/edit', [ChuyenMonController::class, 'edit'])
    ->name('chuyenmon.edit')
    ->middleware('auth');

Route::put('chuyenmon/{chuyenmon}', [ChuyenMonController::class, 'update'])
    ->name('chuyenmon.update')
    ->middleware('auth');

Route::delete('chuyenmon/{chuyenmon}', [ChuyenMonController::class, 'destroy'])
    ->name('chuyenmon.destroy')
    ->middleware('auth');

Route::put('chuyenmon/{chuyenmon}/restore', [ChuyenMonController::class, 'restore'])
    ->name('chuyenmon.restore')
    ->middleware('auth');

// DanToc

Route::get('dantoc', [DanTocController::class, 'index'])
    ->name('dantoc')
    ->middleware('auth');

Route::get('dantoc/create', [DanTocController::class, 'create'])
    ->name('dantoc.create')
    ->middleware('auth');

Route::post('dantoc', [DanTocController::class, 'store'])
    ->name('dantoc.store')
    ->middleware('auth');

Route::get('dantoc/{dantoc}/edit', [DanTocController::class, 'edit'])
    ->name('dantoc.edit')
    ->middleware('auth');

Route::put('dantoc/{dantoc}', [DanTocController::class, 'update'])
    ->name('dantoc.update')
    ->middleware('auth');

Route::delete('dantoc/{dantoc}', [DanTocController::class, 'destroy'])
    ->name('dantoc.destroy')
    ->middleware('auth');

Route::put('dantoc/{dantoc}/restore', [DanTocController::class, 'restore'])
    ->name('dantoc.restore')
    ->middleware('auth');

// LoaiBaoHiem

Route::get('loaibaohiem', [LoaiBaoHiemController::class, 'index'])
    ->name('loaibaohiem')
    ->middleware('auth');

Route::get('loaibaohiem/create', [LoaiBaoHiemController::class, 'create'])
    ->name('loaibaohiem.create')
    ->middleware('auth');

Route::post('loaibaohiem', [LoaiBaoHiemController::class, 'store'])
    ->name('loaibaohiem.store')
    ->middleware('auth');

Route::get('loaibaohiem/{loaibaohiem}/edit', [LoaiBaoHiemController::class, 'edit'])
    ->name('loaibaohiem.edit')
    ->middleware('auth');

Route::put('loaibaohiem/{loaibaohiem}', [LoaiBaoHiemController::class, 'update'])
    ->name('loaibaohiem.update')
    ->middleware('auth');

Route::delete('loaibaohiem/{loaibaohiem}', [LoaiBaoHiemController::class, 'destroy'])
    ->name('loaibaohiem.destroy')
    ->middleware('auth');

Route::put('loaibaohiem/{loaibaohiem}/restore', [LoaiBaoHiemController::class, 'restore'])
    ->name('loaibaohiem.restore')
    ->middleware('auth');

// BaoHiem
Route::get('baohiem', [BaoHiemController::class, 'index'])
    ->name('baohiem')
    ->middleware('auth');

Route::get('baohiem/{nhanvien}/create', [BaoHiemController::class, 'create'])
    ->name('baohiem.create')
    ->middleware('auth');

Route::get('baohiem/{baohiem}/edit', [BaoHiemController::class, 'edit'])
    ->name('baohiem.edit')
    ->middleware('auth');

Route::post('baohiem/{nhanvien}', [BaoHiemController::class, 'store'])
    ->name('baohiem.store')
    ->middleware('auth');

Route::put('baohiem/{baohiem}', [BaoHiemController::class, 'update'])
    ->name('baohiem.update')
    ->middleware('auth');

Route::delete('baohiem/{baohiem}', [BaoHiemController::class, 'destroy'])
    ->name('baohiem.destroy')
    ->middleware('auth');

Route::put('baohiem/{baohiem}/restore', [BaoHiemController::class, 'restore'])
    ->name('baohiem.restore')
    ->middleware('auth');

// NgoaiNgu

Route::get('ngoaingu', [NgoaiNguController::class, 'index'])
    ->name('ngoaingu')
    ->middleware('auth');

Route::get('ngoaingu/create', [NgoaiNguController::class, 'create'])
    ->name('ngoaingu.create')
    ->middleware('auth');

Route::post('ngoaingu', [NgoaiNguController::class, 'store'])
    ->name('ngoaingu.store')
    ->middleware('auth');

Route::get('ngoaingu/{ngoaingu}/edit', [NgoaiNguController::class, 'edit'])
    ->name('ngoaingu.edit')
    ->middleware('auth');

Route::put('ngoaingu/{ngoaingu}', [NgoaiNguController::class, 'update'])
    ->name('ngoaingu.update')
    ->middleware('auth');

Route::delete('ngoaingu/{ngoaingu}', [NgoaiNguController::class, 'destroy'])
    ->name('ngoaingu.destroy')
    ->middleware('auth');

Route::put('ngoaingu/{ngoaingu}/restore', [NgoaiNguController::class, 'restore'])
    ->name('ngoaingu.restore')
    ->middleware('auth');

// PhongBan

Route::get('phongban', [PhongBanController::class, 'index'])
    ->name('phongban')
    ->middleware('auth');

Route::get('phongban/create', [PhongBanController::class, 'create'])
    ->name('phongban.create')
    ->middleware('auth');

Route::post('phongban', [PhongBanController::class, 'store'])
    ->name('phongban.store')
    ->middleware('auth');

Route::get('phongban/{phongban}/edit', [PhongBanController::class, 'edit'])
    ->name('phongban.edit')
    ->middleware('auth');

Route::put('phongban/{phongban}', [PhongBanController::class, 'update'])
    ->name('phongban.update')
    ->middleware('auth');

Route::delete('phongban/{phongban}', [PhongBanController::class, 'destroy'])
    ->name('phongban.destroy')
    ->middleware('auth');

Route::put('phongban/{phongban}/restore', [PhongBanController::class, 'restore'])
    ->name('phongban.restore')
    ->middleware('auth');

// TonGiao

Route::get('tongiao', [TonGiaoController::class, 'index'])
    ->name('tongiao')
    ->middleware('auth');

Route::get('tongiao/create', [TonGiaoController::class, 'create'])
    ->name('tongiao.create')
    ->middleware('auth');

Route::post('tongiao', [TonGiaoController::class, 'store'])
    ->name('tongiao.store')
    ->middleware('auth');

Route::get('tongiao/{tongiao}/edit', [TonGiaoController::class, 'edit'])
    ->name('tongiao.edit')
    ->middleware('auth');

Route::put('tongiao/{tongiao}', [TonGiaoController::class, 'update'])
    ->name('tongiao.update')
    ->middleware('auth');

Route::delete('tongiao/{tongiao}', [TonGiaoController::class, 'destroy'])
    ->name('tongiao.destroy')
    ->middleware('auth');

Route::put('tongiao/{tongiao}/restore', [TonGiaoController::class, 'restore'])
    ->name('tongiao.restore')
    ->middleware('auth');

// MucLuong

Route::get('phucap', [PhuCapController::class, 'index'])
    ->name('phucap')
    ->middleware('auth');

Route::get('phucap/create', [PhuCapController::class, 'create'])
    ->name('phucap.create')
    ->middleware('auth');

Route::post('phucap', [PhuCapController::class, 'store'])
    ->name('phucap.store')
    ->middleware('auth');

Route::get('phucap/{phucap}/edit', [PhuCapController::class, 'edit'])
    ->name('phucap.edit')
    ->middleware('auth');

Route::put('phucap/{phucap}', [PhuCapController::class, 'update'])
    ->name('phucap.update')
    ->middleware('auth');

Route::delete('phucap/{phucap}', [PhuCapController::class, 'destroy'])
    ->name('phucap.destroy')
    ->middleware('auth');

Route::put('phucap/{phucap}/restore', [PhuCapController::class, 'restore'])
    ->name('phucap.restore')
    ->middleware('auth');

// HopDong
Route::get('hopdong', [HopDongController::class, 'index'])
    ->name('hopdong')
    ->middleware('auth');

Route::get('hopdong/{nhanvien}/create', [HopDongController::class, 'create'])
    ->name('hopdong.create')
    ->middleware('auth');

Route::get('hopdong/{hopdong}/edit', [HopDongController::class, 'edit'])
    ->name('hopdong.edit')
    ->middleware('auth');

Route::post('hopdong/{nhanvien}', [HopDongController::class, 'store'])
    ->name('hopdong.store')
    ->middleware('auth');

Route::put('hopdong/{hopdong}', [HopDongController::class, 'update'])
    ->name('hopdong.update')
    ->middleware('auth');

Route::delete('hopdong/{hopdong}', [HopDongController::class, 'destroy'])
    ->name('hopdong.destroy')
    ->middleware('auth');

Route::put('hopdong/{hopdong}/restore', [HopDongController::class, 'restore'])
    ->name('hopdong.restore')
    ->middleware('auth');

// UngLuong
Route::get('ungluong', [UngLuongController::class, 'index'])
    ->name('ungluong')
    ->middleware('auth');

Route::get('ungluong/{nhanvien}/create', [UngLuongController::class, 'create'])
    ->name('ungluong.create')
    ->middleware('auth');

Route::get('ungluong/{ungluong}/edit', [UngLuongController::class, 'edit'])
    ->name('ungluong.edit')
    ->middleware('auth');

Route::post('ungluong/{nhanvien}', [UngLuongController::class, 'store'])
    ->name('ungluong.store')
    ->middleware('auth');

Route::put('ungluong/{ungluong}', [UngLuongController::class, 'update'])
    ->name('ungluong.update')
    ->middleware('auth');

Route::delete('ungluong/{ungluong}', [UngLuongController::class, 'destroy'])
    ->name('ungluong.destroy')
    ->middleware('auth');

Route::put('ungluong/{ungluong}/restore', [UngLuongController::class, 'restore'])
    ->name('ungluong.restore')
    ->middleware('auth');

// NhanLuong

Route::get('nhanluong/tinhluong', [NhanLuongController::class, 'tinhluong'])
    ->name('tinhluong')
    ->middleware('auth');

Route::get('nhanluong', [NhanLuongController::class, 'index'])
    ->name('nhanluong')
    ->middleware('auth');

Route::get('nhanluong/{nhanvien}/create', [NhanLuongController::class, 'create'])
    ->name('nhanluong.create')
    ->middleware('auth');

Route::get('nhanluong/{nhanluong}/edit', [NhanLuongController::class, 'edit'])
    ->name('nhanluong.edit')
    ->middleware('auth');

Route::post('nhanluong/{nhanvien}', [NhanLuongController::class, 'store'])
    ->name('nhanluong.store')
    ->middleware('auth');

Route::put('nhanluong/{nhanluong}', [NhanLuongController::class, 'update'])
    ->name('nhanluong.update')
    ->middleware('auth');

Route::delete('nhanluong/{nhanluong}', [NhanLuongController::class, 'destroy'])
    ->name('nhanluong.destroy')
    ->middleware('auth');

Route::put('nhanluong/{nhanluong}/restore', [NhanLuongController::class, 'restore'])
    ->name('nhanluong.restore')
    ->middleware('auth');

Route::get('nhanluong/export', [NhanLuongController::class, 'export'])
    ->name('nhanluong.export')
    ->middleware('auth');

// Khautru
Route::get('khautru', [KhauTruController::class, 'index'])
    ->name('khautru')
    ->middleware('auth');

Route::get('khautru/{khautru}/edit', [KhauTruController::class, 'edit'])
    ->name('khautru.edit')
    ->middleware('auth');

Route::put('khautru/{khautru}', [KhauTruController::class, 'update'])
    ->name('khautru.update')
    ->middleware('auth');

Route::delete('khautru/{khautru}', [KhauTruController::class, 'destroy'])
    ->name('khautru.destroy')
    ->middleware('auth');

Route::put('khautru/{khautru}/restore', [KhauTruController::class, 'restore'])
    ->name('khautru.restore')
    ->middleware('auth');

// NghiViec
Route::get('nghiviec', [NghiViecController::class, 'index'])
    ->name('nghiviec')
    ->middleware('auth');

Route::get('nghiviec/{nhanvien}/create', [NghiViecController::class, 'create'])
    ->name('nghiviec.create')
    ->middleware('auth');

Route::get('nghiviec/{nghiviec}/edit', [NghiViecController::class, 'edit'])
    ->name('nghiviec.edit')
    ->middleware('auth');

Route::post('nghiviec/{nhanvien}', [NghiViecController::class, 'store'])
    ->name('nghiviec.store')
    ->middleware('auth');

Route::put('nghiviec/{nghiviec}', [NghiViecController::class, 'update'])
    ->name('nghiviec.update')
    ->middleware('auth');

Route::delete('nghiviec/{nghiviec}', [NghiViecController::class, 'destroy'])
    ->name('nghiviec.destroy')
    ->middleware('auth');

Route::put('nghiviec/{nghiviec}/restore', [NghiViecController::class, 'restore'])
    ->name('nghiviec.restore')
    ->middleware('auth');

// NghiViec
Route::get('thuongphat', [ThuongPhatController::class, 'index'])
    ->name('thuongphat')
    ->middleware('auth');

Route::get('thuongphat/{nhanvien}/create', [ThuongPhatController::class, 'create'])
    ->name('thuongphat.create')
    ->middleware('auth');

Route::get('thuongphat/{thuongphat}/edit', [ThuongPhatController::class, 'edit'])
    ->name('thuongphat.edit')
    ->middleware('auth');

Route::post('thuongphat/{nhanvien}', [ThuongPhatController::class, 'store'])
    ->name('thuongphat.store')
    ->middleware('auth');

Route::put('thuongphat/{thuongphat}', [ThuongPhatController::class, 'update'])
    ->name('thuongphat.update')
    ->middleware('auth');

Route::delete('thuongphat/{thuongphat}', [ThuongPhatController::class, 'destroy'])
    ->name('thuongphat.destroy')
    ->middleware('auth');

Route::put('thuongphat/{thuongphat}/restore', [ThuongPhatController::class, 'restore'])
    ->name('thuongphat.restore')
    ->middleware('auth');


// heso
Route::get('heso', [HeSoController::class, 'index'])
    ->name('heso')
    ->middleware('auth');

Route::put('heso', [HeSoController::class, 'update'])
    ->name('heso.update')
    ->middleware('auth');


// bangchamcong
Route::get('bangchamcong', [BangChamCongController::class, 'index'])
    ->name('bangchamcong')
    ->middleware('auth');

Route::post('bangchamcong', [BangChamCongController::class, 'store'])
    ->name('bangchamcong.store')
    ->middleware('auth');
