<?php

namespace App\Http\Controllers;

use App\Models\LoaiBaoHiem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class LoaiBaoHiemController extends Controller
{
    public function index()
    {
        return Inertia::render('LoaiBaoHiem/Index', [
            'filters' => Request::all('search', 'trashed'),
            'loaibaohiem' => (new LoaiBaoHiem())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($loaibaohiem) => [
                    'id' => $loaibaohiem->id,
                    'tenbh' => $loaibaohiem->tenbh,
                    'deleted_at' => $loaibaohiem->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('LoaiBaoHiem/Create');
    }

    public function store()
    {
        Request::validate([
            'tenbh' => ['required', 'max:100', Rule::unique('loaibaohiem', 'tenbh')]
        ]);

        (new LoaiBaoHiem())->create([
            'tenbh' => Request::get('tenbh')
        ]);

        return Redirect::route('loaibaohiem')->with('success', 'Đã tạo thành công.');
    }

    public function edit(LoaiBaoHiem $loaibaohiem)
    {
        return Inertia::render('LoaiBaoHiem/Edit', [
            'loaibaohiem' => [
                'id' => $loaibaohiem->id,
                'tenbh' => $loaibaohiem->tenbh,
                'deleted_at' => $loaibaohiem->deleted_at,
            ],
        ]);
    }

    public function update(LoaiBaoHiem $loaibaohiem)
    {
        Request::validate([
            'tenbh' => ['required', 'max:100', Rule::unique('loaibaohiem')->ignore($loaibaohiem->id)]
        ]);

        $loaibaohiem->update(Request::only('tenbh'));

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(LoaiBaoHiem $loaibaohiem)
    {
        $loaibaohiem->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(LoaiBaoHiem $loaibaohiem)
    {
        $loaibaohiem->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
