<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NghiViec extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'nghiviec';

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function nhanvien()
    {
        return $this->belongsTo(NhanVien::class, 'nhanvien_id', 'id');
    }

    public function checkDateStartEnd($start, $end)
    {
        return strtotime($start) <= strtotime($end) ? true : false;
    }

    public function checkNgayCong($nhanvienId, $start, $end)
    {
        return $this->where('nhanvien_id', $nhanvienId)
                    ->whereBetween('created_at', [date($start), date($end)])
                    ->get()
                    ->count() > 0 ? true : false;
    }
    // a -> b => c trong a -> b hoặc d trong a -> b
    // hoặc
    // a -> b => c trong a -> b and d trong a -> b
    public function exists($nhanvienId, $start, $end, $currentId = -1)
    {
        return $this->where(function($query) use ($nhanvienId, $start, $end, $currentId) {
            return $query
            ->whereBetween('ngaybd', [date($start), date($end)])
            ->whereBetween('ngaykt', [date($start), date($end)])
            ->where('nhanvien_id', $nhanvienId)
            ->where('id', '!=', $currentId);
        })->OrWhere(function($query) use ($nhanvienId, $start, $end, $currentId) {
            return $query
            ->whereBetween('ngaybd', [date($start), date($end)])
            ->WhereNotBetween('ngaykt', [date($start), date($end)])
            ->where('nhanvien_id', $nhanvienId)
            ->where('id', '!=', $currentId);
        })->OrWhere(function($query) use ($nhanvienId, $start, $end, $currentId) {
            return $query
            ->WhereNotBetween('ngaybd', [date($start), date($end)])
            ->WhereBetween('ngaykt', [date($start), date($end)])
            ->where('nhanvien_id', $nhanvienId)
            ->where('id', '!=', $currentId);
        })
        ->get()
        ->count() > 0 ? true : false;
    }

    public function checkNgayNghi($nhanvienId, $ngay)
    {
        return $this->where('ngaybd', '<=', $ngay)
                    ->where('ngaykt', '>=', $ngay)
                    ->where('nhanvien_id', $nhanvienId)
                    ->get()
                    ->count() > 0 ? true : false;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->join('nhanvien as nv', 'nghiviec.nhanvien_id', '=', 'nv.id')
                  ->where('nv.hovaten', 'like', '%'.$search.'%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
