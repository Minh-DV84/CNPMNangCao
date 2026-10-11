<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitBaiTapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,zip', 'max:20480'],
            'ghi_chu' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'file' => 'Tệp bài nộp không hợp lệ.',
            'mimes' => 'Tệp phải có định dạng PDF, DOC, DOCX hoặc ZIP.',
            'max.file' => 'Tệp bài nộp không được vượt quá 20MB.',
            'max.string' => ':attribute không được vượt quá :max ký tự.',
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => 'Tệp bài nộp',
            'ghi_chu' => 'Ghi chú',
        ];
    }
}
