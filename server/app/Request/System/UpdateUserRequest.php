<?php

declare(strict_types=1);

namespace App\Request\System;

use Hyperf\Validation\Request\FormRequest;

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
        return [
            'new_password' => ['required','min:6','max:16','confirmed'],
            'old_password' => ['required','min:5','max:16'],
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.required' => '新密码不能为空',
            'new_password.min' => '新密码长度不能小于6位',
            'new_password.max' => '新密码长度不能大于16位',
            'new_password.confirmed' => '新密码和确认密码不一致',
            'old_password.required' => '旧密码不能为空',
            'old_password.min' => '旧密码长度不能小于5位',
            'old_password.max' => '旧密码长度不能大于16位',
        ];
    }
}
