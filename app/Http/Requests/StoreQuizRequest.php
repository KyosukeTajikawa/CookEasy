<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question'    => ['required', 'string'],
            'choices'     => ['required', 'array', 'size:4'],
            'choices.*'   => ['required', 'string'],
            'answer'      => ['required', 'string', Rule::in($this->input('choices', []))],
        ];
    }
}
