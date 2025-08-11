<?php

declare(strict_types=1);

namespace App\Request\Tool;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class CrontabRequest extends FormRequest
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
            'name' => ['required','string',Rule::unique('crontab')->ignore($this->route('id'))],    
            'type' => ['required','string'],
            'target' => ['required','string'],
            'status' => ['required','integer',Rule::in([1,2])],
            'is_on_one_server' => ['required','integer',Rule::in([1,0])],
            'is_singleton' => ['required','integer',Rule::in([1,0])],
            'remark' => ['nullable','string'],
            'month' => ['nullable','integer'],
            'day' => ['nullable','integer'],
            'week' => ['nullable','integer'],
            'hour' => ['nullable','integer'],
            'minute' => ['nullable','integer'],
            'second' => ['nullable','integer'],
            'task_style' => ['required','integer']
        ];
    }
}
