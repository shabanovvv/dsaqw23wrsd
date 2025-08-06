<?php

namespace app\models\Form;

use yii\base\Model;

class PostForm extends Model
{
    public string $name = '';
    public string $email = '';
    public string $description = '';
    public string $verifyCode = '';

    public function rules(): array
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
            ['name', 'string', 'min' => 2, 'max' => 15],
            ['description', 'trim'],
            ['description', 'string', 'min' => 5, 'max' => 255],
            //['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }
}