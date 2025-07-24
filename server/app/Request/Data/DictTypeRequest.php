<?php

declare(strict_types=1);

namespace App\Request\Data;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class DictTypeRequest extends FormRequest
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
            'name' => ['required',Rule::unique('dict_type','name')->ignore($this->route('id'))],
            'code' => ['required',Rule::unique('dict_type','code')->ignore($this->route('id'))],
            'remark' => ['nullable'],
            'status' => ['required','in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '字典名称不能为空',
            'name.unique' => '字典名称已存在',
            'code.required' => '字典编码不能为空',
            'code.unique' => '字典编码已存在',
            'status.required' => '状态不能为空',
            'status.in' => '状态必须是1或2',
        ];
    }   
}
