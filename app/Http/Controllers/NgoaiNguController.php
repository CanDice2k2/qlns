<?php

namespace App\Http\Controllers;

use App\Models\NgoaiNgu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class NgoaiNguController extends Controller
{
    public function index()
    {
        return Inertia::render('NgoaiNgu/Index', [
            'filters' => Request::all('search', 'trashed'),
            'ngoaingu' => (new NgoaiNgu())
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($ngoaingu) => [
                    'id' => $ngoaingu->id,
                    'tenng' => $ngoaingu->tenng,
                    'deleted_at' => $ngoaingu->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('NgoaiNgu/Create');
    }

    public function store()
    {
        Request::validate([
            'tenng' => ['required', 'max:100', Rule::unique('ngoaingu', 'tenng')]
        ]);

        (new NgoaiNgu())->create([
            'tenng' => Request::get('tenng')
        ]);

        return Redirect::route('ngoaingu')->with('success', 'Đã tạo thành công.');
    }

    public function edit(NgoaiNgu $ngoaingu)
    {
        return Inertia::render('NgoaiNgu/Edit', [
            'ngoaingu' => [
                'id' => $ngoaingu->id,
                'tenng' => $ngoaingu->tenng,
                'deleted_at' => $ngoaingu->deleted_at,
            ],
        ]);
    }

    public function update(NgoaiNgu $ngoaingu)
    {
        Request::validate([
            'tenng' => ['required', 'max:100', Rule::unique('ngoaingu')->ignore($ngoaingu->id)]
        ]);

        $ngoaingu->update(Request::only('tenng'));

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(NgoaiNgu $ngoaingu)
    {
        $ngoaingu->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(NgoaiNgu $ngoaingu)
    {
        $ngoaingu->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
