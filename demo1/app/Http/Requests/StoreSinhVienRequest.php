<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSinhVienRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Chuẩn hoá dữ liệu trước khi validate: chuyển ngày sinh từ dd/mm/yyyy sang Y-m-d.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->ngay_sinh)) {
            $dateParts = explode('/', $this->ngay_sinh);
            if (count($dateParts) === 3) {
                $this->merge(['ngay_sinh' => $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0]]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'msv' => [
                'required',
                'string',
                'size:7',
                'regex:/^[01]{5}68$/',
                'unique:sinh_viens,msv',
            ],
            'ho_ten' => 'required|string|max:255',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'ngay_sinh' => 'required|date|after_or_equal:2005-01-01|before_or_equal:2005-12-31',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
            'so_dien_thoai' => 'required|string|max:20',
        ];
    }

    /**
     * Thông báo lỗi tiếng Việt.
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'string' => ':attribute phải là chuỗi ký tự.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'size' => ':attribute phải đúng :size ký tự.',
            'regex' => ':attribute không đúng định dạng (5 ký tự đầu là 0 hoặc 1, 2 ký tự cuối là 68).',
            'msv.unique' => 'Mã sinh viên này đã tồn tại.',
            'in' => ':attribute phải là Nam hoặc Nữ.',
            'date' => ':attribute không hợp lệ.',
            'date_format' => ':attribute phải theo định dạng dd/mm/yyyy.',
            'after_or_equal' => ':attribute phải trong năm 2005.',
            'before_or_equal' => ':attribute phải trong năm 2005.',
            'exists' => ':attribute không tồn tại trong danh sách lớp học.',
        ];
    }

    /**
     * Tên hiển thị của các trường trong thông báo lỗi.
     */
    public function attributes(): array
    {
        return [
            'msv' => 'Mã sinh viên',
            'ho_ten' => 'Họ và tên',
            'gioi_tinh' => 'Giới tính',
            'ngay_sinh' => 'Ngày sinh',
            'lop_hoc_id' => 'Lớp học',
            'so_dien_thoai' => 'Số điện thoại',
        ];
    }
}
