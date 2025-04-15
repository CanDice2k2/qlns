<?php

namespace App\Http\Controllers;

use App\Models\DanToc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class DanTocController extends Controller
{
    public function index()
    {
        return Inertia::render('DanToc/Index', [
            'filters' => Request::all('search', 'trashed'),
            'dantoc' => (new DanToc())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($dantoc) => [
                    'id' => $dantoc->id,
                    'tendt' => $dantoc->tendt,
                    'deleted_at' => $dantoc->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('DanToc/Create');
    }

    public function store()
    {
        Request::validate([
            'tendt' => ['required', 'max:100', Rule::unique('dantoc', 'tendt')]
        ]);

        (new DanToc())->create([
            'tendt' => Request::get('tendt')
        ]);

        return Redirect::route('dantoc')->with('success', 'Đã tạo thành công.');
    }

    public function edit(DanToc $dantoc)
    {
        return Inertia::render('DanToc/Edit', [
            'dantoc' => [
                'id' => $dantoc->id,
                'tendt' => $dantoc->tendt,
                'deleted_at' => $dantoc->deleted_at,
            ],
        ]);
    }

    public function update(DanToc $dantoc)
    {
        Request::validate([
            'tendt' => ['required', 'max:100', Rule::unique('dantoc')->ignore($dantoc->id)]
        ]);

        $dantoc->update(Request::only('tendt'));

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(DanToc $dantoc)
    {
        $dantoc->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(DanToc $dantoc)
    {
        $dantoc->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
