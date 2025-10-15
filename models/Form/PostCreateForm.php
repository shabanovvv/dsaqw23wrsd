<?php

namespace app\models\Form;

class PostCreateForm extends PostBaseForm
{
    /** @var string */
    public $name = '';
    /** @var string */
    public $email = '';
    /** @var string */
    public $verifyCode = '';

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['name', 'email'], 'required'],
            ['email', 'email'],
            ['name', 'string', 'min' => 2, 'max' => 15],
            ['verifyCode', 'captcha', 'captchaAction' => '/post/captcha'],
        ]);
    }

    public function attributeLabels(): array
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }
}