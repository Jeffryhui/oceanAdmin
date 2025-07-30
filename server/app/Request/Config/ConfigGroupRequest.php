<?php

declare(strict_types=1);

namespace App\Request\Config;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class ConfigGroupRequest extends FormRequest
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
            'code' => ['required','string',Rule::unique('config_group','code')->ignore($this->route('id'))],
            'remark' => ['nullable','string'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => '配置组名称不能为空',
            'code.required' => '配置组编码不能为空',
            'code.unique' => '配置组编码已存在',
            'remark.string' => '配置组备注必须为字符串',
            'name.string' => '配置组名称必须为字符串',
            'code.string' => '配置组编码必须为字符串',
        ];
    }
}
