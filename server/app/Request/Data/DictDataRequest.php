<?php

declare(strict_types=1);

namespace App\Request\Data;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class DictDataRequest extends FormRequest
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
            'type_id' => ['required','numeric'],
            'label' => ['required','string'],
            'value' => ['required','string'],
            'color' => ['nullable','string'],
            'sort' => ['nullable','numeric'],
            'status' => ['required','in:1,2'],
            'remark' => ['nullable','string'],
            'code' => ['required','string'],
        ];
    }

    public function messages(): array
    {
        return [
            'type_id.required' => '字典类型ID不能为空',
            'label.required' => '字典标签不能为空',
            'value.required' => '字典值不能为空',
            'status.required' => '状态不能为空',
            'status.in' => '状态值不正确',
            'remark.string' => '备注必须是字符串',
            'color.string' => '颜色必须是字符串',
            'sort.numeric' => '排序必须是数字', 
            'code.required' => '字典编码不能为空',
            'code.string' => '字典编码必须是字符串',
        ];
    }
}
