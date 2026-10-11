<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiGiang extends Model
{
    use HasFactory;

    protected $table = 'bai_giangs';

    protected $fillable = [
        'mon_hoc_id',
        'tieu_de',
        'mo_ta',
        'file_path',
        'file_name',
    ];

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'mon_hoc_id');
    }
}
