<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiNop extends Model
{
    use HasFactory;

    protected $table = 'bai_nops';

    protected $fillable = [
        'bai_tap_id',
        'sinh_vien_id',
        'file_path',
        'file_name',
        'ghi_chu',
        'nop_luc',
        'diem',
        'nhan_xet',
    ];

    protected function casts(): array
    {
        return [
            'nop_luc' => 'datetime',
            'diem' => 'decimal:2',
        ];
    }

    public function baiTap()
    {
        return $this->belongsTo(BaiTap::class, 'bai_tap_id');
    }

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'sinh_vien_id');
    }
}
