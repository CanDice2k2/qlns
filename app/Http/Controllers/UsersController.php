<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'trashed', 'role'),
            'users' => Auth::user()->nhanvien->user
                ->latest('users.created_at')
                ->filter(Request::only('search', 'trashed', 'role'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'hovaten' => $user->nhanvien->hovaten,
                    'email' => $user->email,
                    'role' => $user->getUserRoleName($user->role),
                    'deleted_at' => $user->deleted_at,
                ]),
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'hovaten' => $user->nhanvien->hovaten,
                'email' => $user->email,
                'role' => $user->role,
                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }

    public function update(User $user)
    {
        Request::validate([
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'role' => ['required', 'between:0,2']
        ]);

        $user->update(Request::only('email', 'role'));

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return Redirect::back()->with('success', 'Đã xoá thành công.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }
}
