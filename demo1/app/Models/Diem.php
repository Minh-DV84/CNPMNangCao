<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diem extends Model
{
    use HasFactory;

    protected $table = 'diems';

    protected $fillable = [
        'sinh_vien_id',
        'mon_hoc_id',
        'diem_chuyen_can',
        'diem_giua_ky',
        'diem_cuoi_ky',
    ];

    protected function casts(): array
    {
        return [
            'diem_chuyen_can' => 'decimal:2',
            'diem_giua_ky' => 'decimal:2',
            'diem_cuoi_ky' => 'decimal:2',
        ];
    }

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'sinh_vien_id');
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'mon_hoc_id');
    }

    public function getDiemTongKetAttribute(): ?float
    {
        if ($this->diem_chuyen_can === null || $this->diem_giua_ky === null || $this->diem_cuoi_ky === null) {
            return null;
        }

        return round(
            ((float) $this->diem_chuyen_can * 0.1)
                + ((float) $this->diem_giua_ky * 0.3)
                + ((float) $this->diem_cuoi_ky * 0.6),
            2,
        );
    }

    public function getXepLoaiAttribute(): ?string
    {
        $diemTongKet = $this->diem_tong_ket;

        if ($diemTongKet === null) {
            return null;
        }

        return match (true) {
            $diemTongKet >= 8.5 => 'Giỏi',
            $diemTongKet >= 7 => 'Khá',
            $diemTongKet >= 5 => 'Trung bình',
            default => 'Yếu',
        };
    }
}
