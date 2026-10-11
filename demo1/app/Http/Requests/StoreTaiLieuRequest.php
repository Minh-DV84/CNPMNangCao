<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaiLieuRequest extends FormRequest
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
            'mo_ta' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip', 'max:20480'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'integer' => ':attribute không hợp lệ.',
            'exists' => ':attribute không tồn tại.',
            'string' => ':attribute phải là chuỗi ký tự.',
            'max.string' => ':attribute không được vượt quá :max ký tự.',
            'file' => 'Tệp đính kèm không hợp lệ.',
            'mimes' => 'Tệp phải có định dạng PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX hoặc ZIP.',
            'max.file' => 'Tệp đính kèm không được vượt quá 20MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'mon_hoc_id' => 'Môn học',
            'tieu_de' => 'Tiêu đề',
            'mo_ta' => 'Mô tả',
            'file' => 'Tệp đính kèm',
        ];
    }
}
