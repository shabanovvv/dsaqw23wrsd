<?php

namespace app\models\Form;

use app\models\Post;
use yii\base\Model;

class PostBaseForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $description = '';
    /** @var bool */
    public $isEdit = false;

    public function rules(): array
    {
        return [
            ['description', 'required'],
            ['description', 'string', 'min' => 5, 'max' => 255],
            ['description', 'trim'],
        ];
    }

    public function loadDataFromPost(Post $post): self
    {
        $this->id = $post->id;
        $this->description = $post->description;
        $this->isEdit = true;

        return $this;
    }
}