<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'cook_time'   => ['required', 'integer', 'min:1'],
            'difficulty'  => ['required', 'in:超簡単,簡単,普通'],
            'images'               => ['nullable', 'array'],
            'images.*'             => ['image', 'mimes:jpeg,png,webp', 'max:2048'],
            'ingredients'          => ['nullable', 'array'],
            'ingredients.*.name'   => ['nullable', 'string', 'max:255'],
            'ingredients.*.quantity' => ['nullable', 'string', 'max:255'],
            'ingredients.*.unit'   => ['nullable', 'string', 'max:50'],
            'steps'                => ['nullable', 'array'],
            'steps.*.description'  => ['nullable', 'string'],
            'step_images'          => ['nullable', 'array'],
            'step_images.*'        => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'タイトルは必須です。',
            'title.string'         => 'タイトルは文字列で入力してください。',
            'title.max'            => 'タイトルは255文字以内で入力してください。',
            'description.required' => '説明は必須です。',
            'description.string'   => '説明は文字列で入力してください。',
            'cook_time.required'   => '調理時間は必須です。',
            'cook_time.integer'    => '調理時間は整数で入力してください。',
            'cook_time.min'        => '調理時間は1分以上で入力してください。',
            'difficulty.required'  => '難易度は必須です。',
            'difficulty.in'        => '難易度は「超簡単」「簡単」「普通」のいずれかを選択してください。',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'       => 'タイトル',
            'description' => '説明',
            'cook_time'   => '調理時間',
            'difficulty'  => '難易度',
        ];
    }
}
