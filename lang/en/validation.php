<?php

return [
    'title' => [
        'required' => 'The title is required.',
        'string'   => 'The title must be a string.',
        'max'      => 'The title must not exceed 255 characters.',
    ],
    'description' => [
        'required' => 'The description is required.',
        'string'   => 'The description must be a string.',
    ],
    'cook_time' => [
        'required' => 'The cook time is required.',
        'integer'  => 'The cook time must be an integer.',
        'min'      => 'The cook time must be at least 1 minute.',
    ],
    'difficulty' => [
        'required' => 'The difficulty is required.',
        'in'       => 'The difficulty must be Very Easy, Easy, or Normal.',
    ],
    'attributes' => [
        'title'       => 'title',
        'description' => 'description',
        'cook_time'   => 'cook time',
        'difficulty'  => 'difficulty',
    ],
];
