<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateSinhVienRequest extends StoreSinhVienRequest
{
    /**
     * Giống lúc thêm mới, chỉ khác: mã sinh viên được phép trùng với chính sinh viên đang sửa.
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'msv' => [
                'required',
                'string',
                'size:7',
                'regex:/^[01]{5}68$/',
                Rule::unique('sinh_viens', 'msv')->ignore($this->route('sinhvien')),
            ],
        ]);
    }
}
