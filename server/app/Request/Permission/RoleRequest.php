<?php

declare(strict_types=1);

namespace App\Request\Permission;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class RoleRequest extends FormRequest
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
            'name' => ['required','string'],
            'code' => ['required','string',Rule::unique('role','code')->ignore($this->route('id'))],
            'status' => ['required','in:1,2'],
            'sort' => ['required','integer'],
            'remark' => ['nullable','string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '角色名称不能为空',
            'name.string' => '角色名称必须是字符串',
            'code.required' => '角色编码不能为空',
            'code.string' => '角色编码必须是字符串',
            'code.unique' => '角色编码已存在',
            'status.required' => '状态不能为空',
            'status.in' => '状态必须是1或2',
            'sort.required' => '排序不能为空',
            'sort.integer' => '排序必须是整数',
            'remark.string' => '备注必须是字符串',
        ];
    }
}
