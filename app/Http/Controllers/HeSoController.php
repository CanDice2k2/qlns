<?php

namespace App\Http\Controllers;

use App\Models\HeSo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class HeSoController extends Controller
{
    public function index()
    {
        $heso = HeSo::find(1);
        return Inertia::render('HeSo/Index', [
            'heso' => [
                'id' => $heso->id,
                'luongcb' => $heso->luongcb,
                'bac1' => $heso->bac1,
                'bac2' => $heso->bac2,
                'bac3' => $heso->bac3,
                'bac4' => $heso->bac4,
                'bac5' => $heso->bac5,
                'bac6' => $heso->bac6,
                'bac7' => $heso->bac7,
                'bac8' => $heso->bac8,
                'bac9' => $heso->bac9,
                'bac10' => $heso->bac10
            ],
        ]);
    }

    public function update()
    {
        HeSo::find(1)->update(
            Request::validate([
                'luongcb' => ['required', 'integer'],
                'bac1' => ['required', 'between:0,100.00'],
                'bac2' => ['required', 'between:0,100.00'],
                'bac3' => ['required', 'between:0,100.00'],
                'bac4' => ['required', 'between:0,100.00'],
                'bac5' => ['required', 'between:0,100.00'],
                'bac6' => ['required', 'between:0,100.00'],
                'bac7' => ['required', 'between:0,100.00'],
                'bac8' => ['required', 'between:0,100.00'],
                'bac9' => ['required', 'between:0,100.00'],
                'bac10' => ['required', 'between:0,100.00']
            ])
        );

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }
}
