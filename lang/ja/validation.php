<?php

return [
    'title' => [
        'required' => 'タイトルは必須です。',
        'string'   => 'タイトルは文字列で入力してください。',
        'max'      => 'タイトルは255文字以内で入力してください。',
    ],
    'description' => [
        'required' => '説明は必須です。',
        'string'   => '説明は文字列で入力してください。',
    ],
    'cook_time' => [
        'required' => '調理時間は必須です。',
        'integer'  => '調理時間は整数で入力してください。',
        'min'      => '調理時間は1分以上で入力してください。',
    ],
    'difficulty' => [
        'required' => '難易度は必須です。',
        'in'       => '難易度は「超簡単」「簡単」「普通」のいずれかを選択してください。',
    ],
    'attributes' => [
        'title'       => 'タイトル',
        'description' => '説明',
        'cook_time'   => '調理時間',
        'difficulty'  => '難易度',
    ],
];
