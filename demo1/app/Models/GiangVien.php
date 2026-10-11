<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiangVien extends Model
{
    use HasFactory;

    protected $table = 'giang_viens';

    protected $fillable = [
        'ma_gv',
        'ho_ten',
        'email',
        'so_dien_thoai',
    ];

    public function lopHocs()
    {
        return $this->hasMany(LopHoc::class, 'giang_vien_id');
    }

    public function monHocs()
    {
        return $this->hasMany(MonHoc::class, 'giang_vien_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'giang_vien_id');
    }
}
