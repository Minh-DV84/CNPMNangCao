<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveDiemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mon_hoc_id' => ['required', 'integer', 'exists:mon_hocs,id'],
            'lop_hoc_id' => ['required', 'integer', 'exists:lop_hocs,id'],
            'diems' => ['required', 'array'],
            'diems.*.sinh_vien_id' => ['required', 'integer', 'exists:sinh_viens,id'],
            'diems.*.diem_chuyen_can' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'diems.*.diem_giua_ky' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'diems.*.diem_cuoi_ky' => ['nullable', 'numeric', 'min:0', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'integer' => ':attribute không hợp lệ.',
            'exists' => ':attribute không tồn tại.',
            'array' => 'Danh sách điểm không hợp lệ.',
            'numeric' => ':attribute phải là số.',
            'min.numeric' => ':attribute không được nhỏ hơn :min.',
            'max.numeric' => ':attribute không được lớn hơn :max.',
        ];
    }

    public function attributes(): array
    {
        return [
            'mon_hoc_id' => 'Môn học',
            'lop_hoc_id' => 'Lớp học',
            'diems.*.diem_chuyen_can' => 'Điểm chuyên cần',
            'diems.*.diem_giua_ky' => 'Điểm giữa kỳ',
            'diems.*.diem_cuoi_ky' => 'Điểm cuối kỳ',
        ];
    }
}
