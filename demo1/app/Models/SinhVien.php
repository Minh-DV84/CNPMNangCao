<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SinhVien extends Model
{
    use HasFactory;

    protected $table = 'sinh_viens';

    protected $fillable = [
        'msv',
        'ho_ten',
        'gioi_tinh',
        'ngay_sinh',
        'lop_hoc_id',
        'so_dien_thoai',
    ];

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'lop_hoc_id');
    }

    public function baiNops()
    {
        return $this->hasMany(BaiNop::class, 'sinh_vien_id');
    }

    public function diems()
    {
        return $this->hasMany(Diem::class, 'sinh_vien_id');
    }
}
