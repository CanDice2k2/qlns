<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhanVien extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'nhanvien';

    protected $fillable = [
        'phucap_id',
        'bangcap_id',
        'chuyenmon_id',
        'ngoaingu_id',
        'dantoc_id',
        'tongiao_id',
        'hovaten',
        'gioitinh',
        'ngaysinh',
        'cmnd',
        'sdt',
        'diachi',
        'quequan',
        'trangthai',
        'ngaynghilam',
        'bacluong',
        'hesoluong',
        'photo_path',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'nhanvien_id', 'id')->withTrashed();
    }

    public function chamcong()
    {
        return $this->hasMany(NhanVien::class, 'id', 'nhanvien_id')->withTrashed();
    }

    public function hopdong()
    {
        return $this->hasMany(HopDong::class, 'id', 'nhanvien_id');
    }

    public function thuongphat()
    {
        return $this->hasMany(ThuongPhat::class, 'id', 'nhanvien_id');
    }

    public function ungluong()
    {
        return $this->hasMany(UngLuong::class, 'id', 'nhanvien_id');
    }

    public function nghiviec()
    {
        return $this->hasMany(NghiViec::class, 'id', 'nhanvien_id');
    }

    public function baohiem()
    {
        return $this->hasMany(BaoHiem::class, 'id', 'nhanvien_id');
    }

    public function khautru()
    {
        return $this->hasMany(KhauTru::class, 'id', 'nhanvien_id');
    }

    public function nhanluong()
    {
        return $this->hasMany(NhanLuong::class, 'id', 'nhanvien_id');
    }

    public function phucap()
    {
        return $this->belongsTo(MucLuong::class, 'phucap_id', 'id');
    }

    public function ngoaingu()
    {
        return $this->belongsTo(NgoaiNgu::class, 'ngoaingu_id', 'id');
    }

    public function chuyenmon()
    {
        return $this->belongsTo(ChuyenMon::class, 'chuyenmon_id', 'id');
    }

    public function bangcap()
    {
        return $this->belongsTo(BangCap::class, 'bangcap_id', 'id');
    }

    public function tongiao()
    {
        return $this->belongsTo(TonGiao::class, 'tongiao_id', 'id');
    }

    public function dantoc()
    {
        return $this->belongsTo(DanToc::class, 'dantoc_id', 'id');
    }

    public function isNgayCong($id, $ngaycong)
    {
        return $this->join('chamcong as c', 'nhanvien.id', '=', 'c.nhanvien_id')
        ->where('c.created_at', $ngaycong . ' 00:00:00')
        ->where('nhanvien.id', $id)
        ->select('nhanvien.id')
        ->first();
    }

    public function ngayCongList(string $ngaycong)
    {
        $list = [];
        $temp = [];
        $nhanVien = $this->join('chamcong as c', 'nhanvien.id', '=', 'c.nhanvien_id')
        ->where('c.created_at', $ngaycong . ' 00:00:00')
        ->orderBy('nhanvien.id', 'ASC')
        ->select('nhanvien.id')
        ->get();
        foreach ($nhanVien as $nv)
            $list[$nv->id - 1] = true;
        if (count($list) > 0)
            for ($i = 0; $i <= max(array_keys($list)); $i++)
                $temp[$i] = $list[$i] ?? null;
        return $temp;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->join('users as u', 'nhanvien.id', '=', 'u.nhanvien_id')
                  ->where('nhanvien.hovaten', 'like', '%'.$search.'%')
                  ->orWhere('nhanvien.sdt', 'like', '%'.$search.'%')
                  ->orWhere('u.email', 'like', '%'.$search.'%');
        })->when($filters['gioitinh'] ?? null, function ($query, $gioitinh) {
                $query->where('gioitinh', $gioitinh === 'nam' ? false : true);
        })->when($filters['trangthai'] ?? null, function ($query, $trangthai) {
                $query->where('trangthai', $trangthai === 'danghilam' ? false : true);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
