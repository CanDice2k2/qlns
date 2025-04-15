<?php

namespace App\Http\Controllers;

use App\Models\ChucVu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class ChucVuController extends Controller
{
    public function index()
    {
        return Inertia::render('ChucVu/Index', [
            'filters' => Request::all('search', 'trashed'),
            'chucvu' => (new ChucVu())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($chucvu) => [
                    'id' => $chucvu->id,
                    'tencv' => $chucvu->tencv,
                    'deleted_at' => $chucvu->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('ChucVu/Create');
    }

    public function store()
    {
        Request::validate([
            'tencv' => ['required', 'max:100', Rule::unique('chucvu', 'tencv')]
        ]);

        (new ChucVu())->create([
            'tencv' => Request::get('tencv')
        ]);

        return Redirect::route('chucvu')->with('success', 'Đã tạo thành công.');
    }

    public function edit(ChucVu $chucvu)
    {
        return Inertia::render('ChucVu/Edit', [
            'chucvu' => [
                'id' => $chucvu->id,
                'tencv' => $chucvu->tencv,
                'deleted_at' => $chucvu->deleted_at,
            ],
        ]);
    }

    public function update(ChucVu $chucvu)
    {
        Request::validate([
            'tencv' => ['required', 'max:100', Rule::unique('chucvu')->ignore($chucvu->id)]
        ]);

        $chucvu->update(Request::only('tencv'));

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(ChucVu $chucvu)
    {
        $chucvu->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(ChucVu $chucvu)
    {
        $chucvu->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
