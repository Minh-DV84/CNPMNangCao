<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,giang_vien,sinh_vien',
            'sinh_vien_id' => 'nullable|exists:sinh_viens,id',
            'giang_vien_id' => 'nullable|exists:giang_viens,id',
        ];
    }

    /**
     * Thông báo lỗi tiếng Việt.
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'email' => ':attribute không hợp lệ.',
            'unique' => ':attribute đã tồn tại.',
            'min' => ':attribute phải có ít nhất :min ký tự.',
            'in' => ':attribute không hợp lệ.',
            'exists' => ':attribute không tồn tại.',
        ];
    }

    /**
     * Tên hiển thị của các trường trong thông báo lỗi.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Tên',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'role' => 'Vai trò',
            'sinh_vien_id' => 'Sinh viên',
            'giang_vien_id' => 'Giảng viên',
        ];
    }
}
