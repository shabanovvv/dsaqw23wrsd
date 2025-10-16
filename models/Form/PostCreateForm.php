<?php

namespace app\models\Form;

/**
 * Форма для создания нового поста.
 * Расширяет базовую форму и добавляет поля name, email, captcha.
 */
class PostCreateForm extends PostBaseForm
{
    /** @var string Имя автора */
    public $name = '';
    /** @var string Email автора */
    public $email = '';
    /** @var string Проверочный код captcha */
    public $verifyCode = '';

    /**
     * Правила валидации описания поста.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['name', 'email'], 'required'],
            ['email', 'email'],
            ['name', 'string', 'min' => 2, 'max' => 15],
            ['verifyCode', 'captcha', 'captchaAction' => '/post/captcha'],
        ]);
    }

    /**
     * Названия атрибутов формы.
     *
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }
}