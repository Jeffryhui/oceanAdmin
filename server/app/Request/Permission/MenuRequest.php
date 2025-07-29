<?php

declare(strict_types=1);

namespace App\Request\Permission;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class MenuRequest extends FormRequest
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
            'code' => ['required','string',Rule::unique('menu','code')->ignore($this->route('id'))],
            'status' => ['required','in:1,2'],
            'icon' => ['nullable','string'],
            'is_hidden' => ['nullable'],
            'is_layout' => ['nullable'],
            'parent_id' => ['nullable'],
            'remark' => ['nullable','string'],
            'route' => ['nullable','string'],
            'sort' => ['nullable','integer'],
            'type' => ['nullable','string'],
            'component' => ['nullable','string']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '菜单名称不能为空',
            'name.string' => '菜单名称必须是字符串',
            'code.required' => '菜单编码不能为空',
            'code.string' => '菜单编码必须是字符串',
            'code.unique' => '菜单编码已存在',
            'status.required' => '状态不能为空',
            'status.in' => '状态必须是1或2',
        ];
    }
}
