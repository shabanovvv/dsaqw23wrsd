<?php

namespace app\models\Form;

use app\models\Post;
use app\models\PostCommonRules;
use yii\base\Model;

class PostForm extends Model
{
    public int $id;
    public string $name = '';
    public string $email = '';
    public string $description = '';
    public string $verifyCode = '';
    public bool $isEdit = false;

    public function rules(): array
    {
        return [
            ...PostCommonRules::rules(),
            //['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    public function loadDataFromPost(Post $post): self
    {
        $this->id = $post->id;
        $this->name = $post->name;
        $this->email = $post->email;
        $this->description = $post->description;

        return $this;
    }

    public function setModeEdit(): self
    {
        $this->isEdit = true;

        return $this;
    }
}