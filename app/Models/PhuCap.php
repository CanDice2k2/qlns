<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PhuCap extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'phucap';

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function phongban()
    {
        return $this->belongsTo(PhongBan::class, 'phongban_id', 'id')->withTrashed();
    }

    public function chucvu()
    {
        return $this->belongsTo(ChucVu::class, 'chucvu_id', 'id')->withTrashed();
    }

    public function nhanvien()
    {
        return $this->hasMany(NhanVien::class, 'id', 'nhanvien_id');
    }

    public function getAll()
    {
        return DB::table('phucap')
            ->join('phongban', 'phucap.phongban_id', '=', 'phongban.id')
            ->join('chucvu', 'phucap.chucvu_id', '=', 'chucvu.id')
            ->select('phucap.id', 'phongban.tenpb', 'chucvu.tencv')
            ->orderBy('phongban.id')
            ->get();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->join('phongban as pb', 'phucap.phongban_id', '=', 'pb.id')
            ->join('chucvu as cv', 'phucap.chucvu_id', '=', 'cv.id')
            ->Where('pb.tenpb', 'like', '%'.$search.'%')
            ->OrWhere('cv.tencv', 'like', '%'.$search.'%')
            ->select('phucap.*', 'pb.tenpb', 'cv.tencv');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
