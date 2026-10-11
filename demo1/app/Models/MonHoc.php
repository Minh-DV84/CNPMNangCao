<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonHoc extends Model
{
    use HasFactory;

    protected $table = 'mon_hocs';

    protected $fillable = [
        'ma_mon',
        'ten_mon',
        'so_tin_chi',
        'giang_vien_id',
    ];

    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'giang_vien_id');
    }

    public function lopHocs()
    {
        return $this->belongsToMany(LopHoc::class, 'lop_hoc_mon_hoc');
    }

    public function baiGiangs()
    {
        return $this->hasMany(BaiGiang::class, 'mon_hoc_id');
    }

    public function taiLieus()
    {
        return $this->hasMany(TaiLieu::class, 'mon_hoc_id');
    }

    public function baiTaps()
    {
        return $this->hasMany(BaiTap::class, 'mon_hoc_id');
    }

    public function diems()
    {
        return $this->hasMany(Diem::class, 'mon_hoc_id');
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->role === 'admin') {
            return $query;
        }

        if ($user->role === 'giang_vien') {
            return $query->where('giang_vien_id', $user->giang_vien_id);
        }

        if ($user->role === 'sinh_vien') {
            $lopHocId = $user->sinhVien?->lop_hoc_id;

            return $query->whereHas('lopHocs', function (Builder $lopHocQuery) use ($lopHocId) {
                $lopHocQuery->whereKey($lopHocId ?? 0);
            });
        }

        return $query->whereKey(0);
    }
}
