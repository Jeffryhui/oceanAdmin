<?php

declare(strict_types=1);

namespace App\Request\Auth;

use Hyperf\Validation\Request\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'code' => ['required'],
            'key' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => '用户名不能为空',
            'password.required' => '密码不能为空',
            'code.required' => '验证码不能为空',
            'key.required' => '验证码key不能为空',
        ];
    }
}
