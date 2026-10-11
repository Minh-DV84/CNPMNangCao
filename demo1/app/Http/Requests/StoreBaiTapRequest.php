<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBaiTapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mon_hoc_id' => ['required', 'integer', 'exists:mon_hocs,id'],
            'tieu_de' => ['required', 'string', 'max:255'],
            'noi_dung' => ['required', 'string'],
            'han_nop' => ['required', 'date'],
            'diem_toi_da' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'integer' => ':attribute phải là số nguyên.',
            'exists' => ':attribute không tồn tại.',
            'string' => ':attribute phải là chuỗi ký tự.',
            'max.string' => ':attribute không được vượt quá :max ký tự.',
            'max.integer' => ':attribute không được lớn hơn :max.',
            'min.integer' => ':attribute phải từ :min trở lên.',
            'date' => ':attribute không hợp lệ.',
        ];
    }

    public function attributes(): array
    {
        return [
            'mon_hoc_id' => 'Môn học',
            'tieu_de' => 'Tiêu đề',
            'noi_dung' => 'Nội dung',
            'han_nop' => 'Hạn nộp',
            'diem_toi_da' => 'Điểm tối đa',
        ];
    }
}
