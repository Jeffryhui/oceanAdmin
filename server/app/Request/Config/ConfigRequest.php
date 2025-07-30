<?php

declare(strict_types=1);

namespace App\Request\Config;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class ConfigRequest extends FormRequest
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
            'group_id' => ['required','integer'],
            'name' => ['required','string'],
            'key' => ['required','string',Rule::unique('config','key')->ignore($this->route('id'))],
            'value' => ['nullable'],
            'input_type' => ['required','string'],
            'config_select_data' => ['nullable'],
            'sort' => ['integer'],
            'remark' => ['nullable','string'],
        ];
    }
    public function messages(): array
    {
        return [
            'group_id.required' => '配置组ID不能为空',
            'group_id.integer' => '配置组ID必须为整数',
            'name.required' => '配置名称不能为空',
            'name.string' => '配置名称必须为字符串',
            'key.required' => '配置键不能为空',
            'key.string' => '配置键必须为字符串',
            'key.unique' => '配置键已存在',
            'input_type.required' => '数据输入类型不能为空',
            'input_type.string' => '数据输入类型必须为字符串',
            'sort.integer' => '排序必须为整数',
            'remark.string' => '备注必须为字符串',
        ];
    }
}
