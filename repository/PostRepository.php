<?php

namespace app\repository;

use app\models\Post;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class PostRepository
{
    /**
     * @throws NotFoundHttpException
     */
    public function findById(int $postId): ?Post
    {
        return Post::findOne($postId);
    }

    public function findAll(): array
    {
        return Post::find()->all();
    }

    public function findCountPostsByIp(array $ip): array
    {
        return Post::find()
            ->select(['ip', 'COUNT(id) AS count'])
            ->groupBy('ip')
            ->where(['in', 'ip', $ip])
            ->asArray()
            ->all();
    }

    public function findLastPostByIp(string $ip): ?Post
    {
        return Post::find()
            ->where(['ip' => $ip])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
    }

    /**
     * @throws Exception
     */
    public function save(Post $post): bool
    {
        return $post->save();
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function delete(int $postId): bool
    {
        $post = $this->findById($postId);
        $post->delete();

        /**
         * доп проверка soft delete
         */
        if ($post->isDelete() !== null) {
            return true;
        } else {
            return false;
        }
    }
}