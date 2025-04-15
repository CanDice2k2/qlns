<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function nhanvien()
    {
        return $this->belongsTo(NhanVien::class)->withTrashed();
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    public function getUserRoleName($role)
    {
        switch ($role) {
            case 2:
                return 'Quản trị viên';
            case 1:
                return 'Quản lý';
        }

        return 'Người dùng';
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->join('nhanvien as nv', 'users.nhanvien_id', '=', 'nv.id')
            ->Where('users.email', 'like', '%'.$search.'%')
            ->orWhere('nv.hovaten', 'like', '%'.$search.'%');
        })->when($filters['role'] ?? null, function ($query, $role) {
                if ($role === 'quantrivien') {
                    $query->where('role', 2);
                } elseif ($role === 'quanly') {
                    $query->where('role', 1);
                } elseif ($role === 'nguoidung') {
                    $query->where('role', 0);
                }
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
