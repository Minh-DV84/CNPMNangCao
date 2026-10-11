<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiTap extends Model
{
    use HasFactory;

    protected $table = 'bai_taps';

    protected $fillable = [
        'mon_hoc_id',
        'tieu_de',
        'noi_dung',
        'han_nop',
        'diem_toi_da',
    ];

    protected function casts(): array
    {
        return [
            'han_nop' => 'datetime',
            'diem_toi_da' => 'integer',
        ];
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'mon_hoc_id');
    }

    public function baiNops()
    {
        return $this->hasMany(BaiNop::class, 'bai_tap_id');
    }
}
