<?php

namespace app\models\Form;

use app\models\Post;

/**
 * Форма для редактирования поста.
 * Наследует базовую и добавляет поля name и email.
 */
class PostEditForm extends PostBaseForm
{
    /** @var string Имя автора */
    public $name = '';
    /** @var string Email автора */
    public $email = '';

    /**
     * Загружает данные из модели Post, включая имя и email.
     *
     * @param Post $post
     * @return $this
     */
    public function loadDataFromPost(Post $post): self
    {
        parent::loadDataFromPost($post);
        $this->name = $post->name;
        $this->email = $post->email;
        return $this;
    }
}