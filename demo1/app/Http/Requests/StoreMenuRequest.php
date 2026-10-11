<?php

namespace App\Http\Requests;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'ten' => 'required|string|max:100',
            // Rule regex có chứa dấu | nên phải viết dạng mảng thay vì chuỗi ngăn cách bằng |
            'url' => ['required', 'string', 'max:255', 'regex:/^(\/|#|https?:\/\/)/'],
            'vi_tri' => ['required', Rule::in(array_keys(Menu::VI_TRI))],
            'nhom' => 'nullable|string|max:100',
            'thu_tu' => 'required|integer|min:0',
            'trang_thai' => 'required|boolean',
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
            'integer' => ':attribute phải là số nguyên.',
            'boolean' => ':attribute không hợp lệ.',
            'in' => ':attribute không hợp lệ.',
            'url.regex' => 'Đường dẫn phải bắt đầu bằng /, # hoặc http(s)://.',
            'thu_tu.min' => 'Thứ tự phải từ :min trở lên.',
        ];
    }

    /**
     * Tên hiển thị của các trường trong thông báo lỗi.
     */
    public function attributes(): array
    {
        return [
            'ten' => 'Tên menu',
            'url' => 'Đường dẫn',
            'vi_tri' => 'Vị trí',
            'nhom' => 'Nhóm',
            'thu_tu' => 'Thứ tự',
            'trang_thai' => 'Trạng thái',
        ];
    }
}
