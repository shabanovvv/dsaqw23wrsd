<?php

namespace app\models;

class PostCommonRules
{
    public static function rules(): array
    {
        return [
            [['name', 'email'], 'required', 'when' => function ($model) {
                return !$model->isEdit;
            }],
            [['description'], 'required'],
            ['email', 'email'],
            ['name', 'string', 'min' => 2, 'max' => 15],
            ['description', 'trim'],
            ['description', 'string', 'min' => 5, 'max' => 255],
        ];
    }
}