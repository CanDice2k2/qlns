<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\ChamCong;
use App\Models\NghiViec;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class BangChamCongController extends Controller
{
    public function index()
    {
        if (!empty(Request::get('ngaycong')) && preg_match('/([0-9]{4,4}+)\-([0-9]{2,2}+)\-([0-9]{2,2}+)/', Request::get('ngaycong')))
            $ngaycong = Request::get('ngaycong');
        else
            $ngaycong = date('Y-m-d', time());

        return Inertia::render('BangChamCong/Index', [
            'ngaycong' => $ngaycong,
            'chamconglist' => Auth::user()->nhanvien->ngayCongList($ngaycong),
            'filters' => Request::all('search'),
            'nhanvien' => Auth::user()->nhanvien
                ->latest('nhanvien.created_at')
                ->filter(Request::only('search'))
                ->paginate(1000)
                ->withQueryString()
                ->through(fn ($nhanvien) => [
                    'id' => $nhanvien->id,
                    'manv' => 'NV' . str_pad($nhanvien->id, 3, '0', STR_PAD_LEFT),
                    'hovaten' => $nhanvien->hovaten,
                    'email' => $nhanvien->user->email,
                    'nghiviec' => (new NghiViec())->checkNgayNghi($nhanvien->id, $ngaycong)
                ]),
        ]);
    }

    public function store()
    {
        Request::validate([
            'ngaycong' => ['required', 'date']
        ]);

        $list = Request::get('nhanvienIDList');
        $ngaycong = Request::get('ngaycong');
        $chamcong = new ChamCong();

        if (count($list) <= 0)
            return Redirect::back()->with('error', 'Bạn chưa chọn chấm công cho nhân viên nào cả.');

        foreach ($list as $id => $isTrue)
        {
            if (empty(Auth::user()->nhanvien->isNgayCong($id + 1, $ngaycong)))
            {
                if ($isTrue)
                {
                    if (!(new NghiViec())->checkNgayNghi($id + 1, $ngaycong))
                    {
                        $chamcong->create([
                            'nhanvien_id' => $id + 1,
                            'created_at' => $ngaycong . ' 00:00:00'
                        ]);
                    }
                }
            }
            else
            {
                if (!$isTrue)
                {
                    $chamcong->where('nhanvien_id', $id + 1)->where('created_at', $ngaycong . ' 00:00:00')->forceDelete();
                }
            }
        }

        return Redirect::back()->with('success', 'Chấm công cho nhân viên thành công.');
    }
}
