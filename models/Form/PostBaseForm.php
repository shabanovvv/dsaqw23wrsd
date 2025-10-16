<?php

namespace app\models\Form;

use app\models\Post;
use yii\base\Model;

/**
 * Базовая форма для постов.
 * Содержит общие поля и логику для создания и редактирования.
 */
class PostBaseForm extends Model
{
    /** @var int ID поста */
    public $id;
    /** @var string Текст сообщения */
    public $description = '';
    /** @var bool Флаг, указывающий, что форма используется для редактирования */
    public $isEdit = false;

    /**
     * Правила валидации описания поста.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            ['description', 'required'],
            ['description', 'string', 'min' => 5, 'max' => 255],
            ['description', 'trim'],
        ];
    }

    /**
     * Загружает данные из модели Post в форму.
     *
     * @param Post $post
     * @return $this
     */
    public function loadDataFromPost(Post $post): self
    {
        $this->id = $post->id;
        $this->description = $post->description;
        $this->isEdit = true;

        return $this;
    }
}