<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SinhVienResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'msv' => $this->msv,
            'ho_ten' => $this->ho_ten,
            'gioi_tinh' => $this->gioi_tinh,
            'ngay_sinh' => $this->ngay_sinh,
            'lop_hoc_id' => $this->lop_hoc_id,
            'so_dien_thoai' => $this->so_dien_thoai,
            'lop_hoc' => $this->whenLoaded('lopHoc', function () {
                return [
                    'id' => $this->lopHoc->id,
                    'ten_lop' => $this->lopHoc->ten_lop,
                    'ma_lop' => $this->lopHoc->ma_lop,
                ];
            }),
        ];
    }
}
