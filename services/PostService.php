<?php

namespace app\services;

use app\events\dispatcher\EventDispatcher;
use app\events\PostCreatedEvent;
use app\exceptions\EntityNotFoundException;
use app\models\Form\PostBaseForm;
use app\models\Post;
use app\repository\PostRepository;
use app\exceptions\ValidationException;
use DomainException;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * Сервис для управления постами — бизнес-логика приложения.
 */
readonly class PostService
{
    public function __construct(
        private PostRepository $postRepository,
        private EventDispatcher $dispatcher,
    ) {
    }

    /**
     * Поиск поста по ID.
     *
     * @param int $postId
     * @return Post
     */
    public function findById(int $postId): Post
    {
        $post = $this->postRepository->findById($postId);
        if ($post === null) {
            throw new EntityNotFoundException(Yii::t('app', 'post_not_exist'));
        }

        return $post;
    }

    /**
     * Получить все посты.
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->postRepository->findAll();
    }

    /**
     * Получить посты с пагинацией.
     *
     * @param int $pageSize
     * @return array
     */
    public function findAllPaginated(int $pageSize = 10): array
    {
        return $this->postRepository->findAllWithPagination($pageSize);
    }

    /**
     * Подсчитать количество постов по IP.
     *
     * @param array $posts
     * @return array
     */
    public function findCountPosts(array $posts): array
    {
        $ipAddresses = $this->findUniqueIPs($posts);
        $posts = $this->postRepository->findCountPostsByIp($ipAddresses);

        $ipCounts = [];
        foreach ($posts as $post) {
            $ipCounts[$post['ip']] = $post['count'];
        }

        return $ipCounts;
    }

    /**
     * Найти последний пост по IP.
     *
     * @param string $ip
     * @return Post|null
     */
    public function findLastPostByIp(string $ip): ?Post
    {
        return $this->postRepository->findLastPostByIp($ip);
    }

    /**
     * Создать новый пост из формы.
     *
     * @param PostBaseForm $postForm
     * @param string $ip
     * @return Post
     * @throws Exception
     */
    public function createPostFromForm(PostBaseForm $postForm, string $ip): Post
    {
        $post = new Post();
        $post->ip = $ip;
        $post->loadDataFromPostForm($postForm);

        if (!$this->postRepository->save($post)) {
            $errorsSummary = [];
            foreach ($post->getErrors() as $attribute => $errors) {
                $errorsSummary[$attribute] = implode(', ', $errors);
            }
            throw new ValidationException($errorsSummary);
        }
        $this->dispatcher->dispatch(new PostCreatedEvent($post));

        return $post;
    }

    /**
     * Обновить существующий пост.
     *
     * @param int $postId
     * @param PostBaseForm $postForm
     * @return bool
     * @throws Exception
     */
    public function updatePost(int $postId, PostBaseForm $postForm): bool
    {
        $post = $this->findById($postId);
        $post->loadDataFromPostForm($postForm);

        if (!$this->postRepository->save($post)) {
            $errorsSummary = [];
            foreach($post->errors as $key => $errors) {
                $errorsSummary[$key] = implode(', ', $errors);
            }
            throw new ValidationException($errorsSummary);
        }

        return true;
    }

    /**
     * Удалить пост по ID.
     *
     * @param int $postId
     * @return void
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function deletePost(int $postId): void
    {
        if (!$this->postRepository->delete($postId)) {
            throw new DomainException('Unable to delete post from database');
        }
    }

    /**
     * Получить уникальные IP из списка постов.
     *
     * @param array $posts
     * @return array
     */
    private function findUniqueIPs(array $posts): array
    {
        return array_unique(array_column($posts, 'ip'));
    }
}