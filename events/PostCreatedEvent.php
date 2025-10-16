<?php

namespace app\events;

use app\models\Post;

/**
 * Событие, возникающее после создания поста.
 */
readonly class PostCreatedEvent
{
    /**
     * @param Post $post Созданный пост
     */
    public function __construct(
        public Post $post
    ) {}
}