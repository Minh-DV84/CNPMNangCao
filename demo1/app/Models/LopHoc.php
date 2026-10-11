<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LopHoc extends Model
{
    //
    use HasFactory;
    protected $table = 'lop_hocs';
    protected $fillable = [
        'ten_lop',
        'ma_lop',
        'giao_vien',
        'so_dien_thoai_gvcn',
        'ghi_chu',
        'si_so',
        'trang_thai',
        'giang_vien_id',
    ];

    public function sinhViens()
    {
        return $this->hasMany(SinhVien::class, 'lop_hoc_id');
    }

    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'giang_vien_id');
    }

    public function monHocs()
    {
        return $this->belongsToMany(MonHoc::class, 'lop_hoc_mon_hoc');
    }
}
