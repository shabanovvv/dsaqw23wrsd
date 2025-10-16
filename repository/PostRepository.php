<?php

namespace app\repository;

use app\models\Post;
use Throwable;
use yii\data\Pagination;
use yii\db\Exception;
use yii\db\StaleObjectException;

/**
 * Репозиторий для работы с моделью Post.
 * Отвечает за получение и сохранение данных на уровне БД.
 */
class PostRepository
{
    /**
     * Найти пост по ID.
     *
     * @param int $postId
     * @return Post|null
     */
    public function findById(int $postId): ?Post
    {
        return Post::findOne($postId);
    }

    /**
     * Получить все посты (без пагинации).
     *
     * @return array
     */
    public function findAll(): array
    {
        return Post::find()->all();
    }

    /**
     * Получить список постов с пагинацией.
     * Возвращает массив [список постов, объект Pagination].
     *
     * @param int $pageSize
     * @return array
     */
    public function findAllWithPagination(int $pageSize = 10): array
    {
        $query = Post::find()->orderBy(['created_at' => SORT_DESC]);
        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => $pageSize,
        ]);

        $posts = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [$posts, $pagination];
    }

    /**
     * Подсчитать количество постов по каждому IP.
     * Возвращает массив с ключами: ip, count.
     *
     * @param array $ip
     * @return array
     */
    public function findCountPostsByIp(array $ip): array
    {
        return Post::find()
            ->select(['ip', 'COUNT(id) AS count'])
            ->groupBy('ip')
            ->where(['in', 'ip', $ip])
            ->asArray()
            ->all();
    }

    /**
     * Найти последний пост по IP.
     *
     * @param string $ip
     * @return Post|null
     */
    public function findLastPostByIp(string $ip): ?Post
    {
        /** @var Post */
        return Post::find()
            ->where(['ip' => $ip])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
    }

    /**
     * Сохранить пост (insert или update).
     *
     * @param Post $post
     * @return bool
     * @throws Exception
     */
    public function save(Post $post): bool
    {
        return $post->save();
    }

    /**
     * Удалить пост по ID (используется soft delete).
     *
     * @param int $postId
     * @return bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function delete(int $postId): bool
    {
        $post = $this->findById($postId);
        $post->delete();

        // Проверяем, помечен ли пост как удалённый (soft delete)
        return $post->isDelete() !== null;
    }
}