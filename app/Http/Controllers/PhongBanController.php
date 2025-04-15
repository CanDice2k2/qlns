<?php

namespace App\Http\Controllers;

use App\Models\PhongBan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class PhongBanController extends Controller
{
    public function index()
    {
        return Inertia::render('PhongBan/Index', [
            'filters' => Request::all('search', 'trashed'),
            'phongban' => (new PhongBan())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($phongban) => [
                    'id' => $phongban->id,
                    'tenpb' => $phongban->tenpb,
                    'deleted_at' => $phongban->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('PhongBan/Create');
    }

    public function store()
    {
        Request::validate([
            'tenpb' => ['required', 'max:100', Rule::unique('phongban', 'tenpb')]
        ]);

        (new PhongBan())->create([
            'tenpb' => Request::get('tenpb')
        ]);

        return Redirect::route('phongban')->with('success', 'Đã tạo thành công.');
    }

    public function edit(PhongBan $phongban)
    {
        return Inertia::render('PhongBan/Edit', [
            'phongban' => [
                'id' => $phongban->id,
                'tenpb' => $phongban->tenpb,
                'deleted_at' => $phongban->deleted_at,
            ],
        ]);
    }

    public function update(PhongBan $phongban)
    {
        Request::validate([
            'tenpb' => ['required', 'max:100', Rule::unique('phongban')->ignore($phongban->id)]
        ]);

        $phongban->update(Request::only('tenpb'));

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(PhongBan $phongban)
    {
        $phongban->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(PhongBan $phongban)
    {
        $phongban->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
