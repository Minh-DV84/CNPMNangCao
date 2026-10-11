<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeBaiNopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'diem' => ['required', 'numeric', 'min:0'],
            'nhan_xet' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'numeric' => ':attribute phải là số.',
            'min.numeric' => ':attribute không được âm.',
            'max.string' => ':attribute không được vượt quá :max ký tự.',
        ];
    }

    public function attributes(): array
    {
        return [
            'diem' => 'Điểm',
            'nhan_xet' => 'Nhận xét',
        ];
    }
}
