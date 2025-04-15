<?php

namespace App\Http\Controllers;

use App\Models\BangCap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class BangCapController extends Controller
{
    public function index()
    {
        return Inertia::render('BangCap/Index', [
            'filters' => Request::all('search', 'trashed'),
            'bangcap' => (new BangCap())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($bangcap) => [
                    'id' => $bangcap->id,
                    'tenbc' => $bangcap->tenbc,
                    'deleted_at' => $bangcap->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('BangCap/Create');
    }

    public function store()
    {
        Request::validate([
            'tenbc' => ['required', 'max:100', Rule::unique('bangcap', 'tenbc')]
        ]);

        (new BangCap())->create([
            'tenbc' => Request::get('tenbc')
        ]);

        return Redirect::route('bangcap')->with('success', 'Đã tạo thành công.');
    }

    public function edit(BangCap $bangcap)
    {
        return Inertia::render('BangCap/Edit', [
            'bangcap' => [
                'id' => $bangcap->id,
                'tenbc' => $bangcap->tenbc,
                'deleted_at' => $bangcap->deleted_at,
            ],
        ]);
    }

    public function update(BangCap $bangcap)
    {
        Request::validate([
            'tenbc' => ['required', 'max:100', Rule::unique('bangcap')->ignore($bangcap->id)]
        ]);

        $bangcap->update(Request::only('tenbc'));

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(BangCap $bangcap)
    {
        $bangcap->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(BangCap $bangcap)
    {
        $bangcap->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
