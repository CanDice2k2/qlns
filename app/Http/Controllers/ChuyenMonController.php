<?php

namespace App\Http\Controllers;

use App\Models\ChuyenMon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class ChuyenMonController extends Controller
{
    public function index()
    {
        return Inertia::render('ChuyenMon/Index', [
            'filters' => Request::all('search', 'trashed'),
            'chuyenmon' => (new ChuyenMon())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($chuyenmon) => [
                    'id' => $chuyenmon->id,
                    'tencm' => $chuyenmon->tencm,
                    'deleted_at' => $chuyenmon->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('ChuyenMon/Create');
    }

    public function store()
    {
        Request::validate([
            'tencm' => ['required', 'max:100', Rule::unique('chuyenmon', 'tencm')]
        ]);

        (new ChuyenMon())->create([
            'tencm' => Request::get('tencm')
        ]);

        return Redirect::route('chuyenmon')->with('success', 'Đã tạo thành công.');
    }

    public function edit(ChuyenMon $chuyenmon)
    {
        return Inertia::render('ChuyenMon/Edit', [
            'chuyenmon' => [
                'id' => $chuyenmon->id,
                'tencm' => $chuyenmon->tencm,
                'deleted_at' => $chuyenmon->deleted_at,
            ],
        ]);
    }

    public function update(ChuyenMon $chuyenmon)
    {
        Request::validate([
            'tencm' => ['required', 'max:100', Rule::unique('chuyenmon')->ignore($chuyenmon->id)]
        ]);

        $chuyenmon->update(Request::only('tencm'));

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(ChuyenMon $chuyenmon)
    {
        $chuyenmon->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(ChuyenMon $chuyenmon)
    {
        $chuyenmon->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
