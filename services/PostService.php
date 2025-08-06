<?php

namespace app\services;

use app\models\Form\PostForm;
use app\models\Post;
use yii\db\Exception;

class PostService
{
    /**
     * @throws Exception
     */
    public function save(PostForm $postForm): bool
    {
        $post = new Post();
        $post->name = $postForm->name;
        $post->email = $postForm->email;
        $post->description = $postForm->description;

        return $post->save();
    }
}