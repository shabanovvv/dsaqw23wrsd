<?php

namespace app\events;

use app\models\Post;

readonly class PostCreatedEvent
{
    public function __construct(
        public Post $post
    ) {}
}