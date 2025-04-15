<?php

namespace App\Http\Controllers;

use App\Models\TonGiao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class TonGiaoController extends Controller
{
    public function index()
    {
        return Inertia::render('TonGiao/Index', [
            'filters' => Request::all('search', 'trashed'),
            'tongiao' => (new TonGiao())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($tongiao) => [
                    'id' => $tongiao->id,
                    'tentg' => $tongiao->tentg,
                    'deleted_at' => $tongiao->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('TonGiao/Create');
    }

    public function store()
    {
        Request::validate([
            'tentg' => ['required', 'max:100', Rule::unique('tongiao', 'tentg')]
        ]);

        (new TonGiao())->create([
            'tentg' => Request::get('tentg')
        ]);

        return Redirect::route('tongiao')->with('success', 'Đã tạo thành công.');
    }

    public function edit(TonGiao $tongiao)
    {
        return Inertia::render('TonGiao/Edit', [
            'tongiao' => [
                'id' => $tongiao->id,
                'tentg' => $tongiao->tentg,
                'deleted_at' => $tongiao->deleted_at,
            ],
        ]);
    }

    public function update(TonGiao $tongiao)
    {
        Request::validate([
            'tentg' => ['required', 'max:100', Rule::unique('tongiao')->ignore($tongiao->id)]
        ]);

        $tongiao->update(Request::only('tentg'));

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(TonGiao $tongiao)
    {
        $tongiao->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(TonGiao $tongiao)
    {
        $tongiao->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
