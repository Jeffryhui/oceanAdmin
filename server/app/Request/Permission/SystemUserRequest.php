<?php

declare(strict_types=1);

namespace App\Request\Permission;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class SystemUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected array $scenes = [
        'store' => ['username','avatar','password','nickname','email','phone','status','role_ids','remark'],
        'update' => ['username','avatar','nickname','email','phone','status','role_ids','remark'],
    ];

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => ['required','string',Rule::unique('system_user','username')->ignore($this->route('id'))],
            'avatar' => ['required','string'],
            'password' => ['required','min:6','max:16'],
            'nickname' => ['required','string'],
            'email' => ['nullable',Rule::unique('system_user','email')->ignore($this->route('id'))],
            'phone' => ['nullable',Rule::unique('system_user','phone')->ignore($this->route('id'))],
            'status' => ['required','in:1,2'],
            'role_ids' => ['required','array'],
            'remark' => ['nullable','string'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => '用户名不能为空',
            'username.unique' => '用户名已存在',
            'username.string' => '用户名只能是字符串',
            'avatar.required' => '头像不能为空',
            'password.required' => '密码不能为空',
            'password.min' => '密码长度不能小于6位',
            'password.max' => '密码长度不能大于16位',
            'nickname.required' => '昵称不能为空',
            'nickname.string' => '昵称只能是字符串',
            'email.unique' => '邮箱已存在',
            'phone.unique' => '手机号已存在',
            'status.required' => '状态不能为空',
            'status.in' => '状态值不正确',
            'role_ids.required' => '角色不能为空',
            'role_ids.array' => '角色值不正确',
            'remark.string' => '备注值不正确',
        ];
    }
}
