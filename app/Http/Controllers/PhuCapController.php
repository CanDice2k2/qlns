<?php

namespace App\Http\Controllers;

use App\Models\ChucVu;
use App\Models\PhuCap;
use App\Models\PhongBan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class PhuCapController extends Controller
{
    public function index()
    {
        return Inertia::render('PhuCap/Index', [
            'filters' => Request::all('search', 'trashed'),
            'phucap' => (new PhuCap())
                ->latest('phucap.created_at')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($phucap) => [
                    'id' => $phucap->id,
                    'tenpb' => $phucap->phongban->tenpb,
                    'tencv' => $phucap->chucvu->tencv,
                    'hsphucap' => $phucap->hsphucap,
                    'deleted_at' => $phucap->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('PhuCap/Create', [
            'phongban' => (new PhongBan())
                ->orderBy('tenpb')
                ->get()
                ->map
                ->only('id', 'tenpb'),
            'chucvu' => (new ChucVu())
                ->orderBy('tencv')
                ->get()
                ->map
                ->only('id', 'tencv')
        ]);
    }

    public function store()
    {
        Request::validate([
            'phongban' => ['required', Rule::exists('phongban', 'id'), Rule::unique('phucap', 'phongban_id')->where('chucvu_id', Request::get('chucvu'))],
            'chucvu' => ['required', Rule::exists('chucvu', 'id'), Rule::unique('phucap', 'chucvu_id')->where('phongban_id', Request::get('phongban'))],
            'hsphucap' => ['required', 'between:0,100.00'],
        ]);

        (new PhuCap())->create([
            'phongban_id' => Request::get('phongban'),
            'chucvu_id' => Request::get('chucvu'),
            'hsphucap' => Request::get('hsphucap'),
        ]);

        return Redirect::route('phucap')->with('success', 'Đã tạo thành công.');
    }

    public function edit(PhuCap $phucap)
    {
        return Inertia::render('PhuCap/Edit', [
            'phongban' => (new PhongBan())
                ->orderBy('tenpb')
                ->get()
                ->map
                ->only('id', 'tenpb'),
            'chucvu' => (new ChucVu())
                ->orderBy('tencv')
                ->get()
                ->map
                ->only('id', 'tencv'),
            'phucap' => [
                'id' => $phucap->id,
                'phongban' => $phucap->phongban_id,
                'chucvu' => $phucap->chucvu_id,
                'hsphucap' => $phucap->hsphucap,
                'deleted_at' => $phucap->deleted_at,
            ],
        ]);
    }

    public function update(PhuCap $phucap)
    {
        Request::validate([
            'phongban' => ['required', Rule::exists('phongban', 'id'), Rule::unique('phucap', 'phongban_id')->where('chucvu_id', Request::get('chucvu'))->ignore($phucap->id)],
            'chucvu' => ['required', Rule::exists('chucvu', 'id'), Rule::unique('phucap', 'chucvu_id')->where('phongban_id', Request::get('phongban'))->ignore($phucap->id)],
            'hsphucap' => ['required', 'between:0,100.00'],
        ]);

        $phucap->update([
            'phongban_id' => Request::get('phongban'),
            'chucvu_id' => Request::get('chucvu'),
            'hsphucap' => Request::get('hsphucap'),
        ]);

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(PhuCap $phucap)
    {
        $phucap->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(PhuCap $phucap)
    {
        $phucap->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
