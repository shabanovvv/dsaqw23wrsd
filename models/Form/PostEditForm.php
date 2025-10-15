<?php

namespace app\models\Form;

use app\models\Post;

class PostEditForm extends PostBaseForm
{
    /** @var string */
    public $name = '';
    /** @var string */
    public $email = '';

    public function loadDataFromPost(Post $post): self
    {
        parent::loadDataFromPost($post);
        $this->name = $post->name;
        $this->email = $post->email;
        return $this;
    }
}