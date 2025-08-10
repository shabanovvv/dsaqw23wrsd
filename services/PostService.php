<?php

namespace app\services;

use app\models\Form\PostForm;
use app\models\Post;
use app\repository\PostRepository;
use app\exceptions\ValidationException;
use DomainException;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

readonly class PostService
{
    public function __construct(
        private EmailService $emailService,
        private PostRepository $postRepository,
    ) {
    }

    /**
     * @throws NotFoundHttpException
     */
    public function findById(int $postId): Post
    {
        $post = $this->postRepository->findById($postId);
        if ($post === null) {
            throw new NotFoundHttpException(Yii::t('app', 'post_not_exist'));
        }

        return $post;
    }

    public function findAll(): array
    {
        return $this->postRepository->findAll();
    }

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

    private function findUniqueIPs(array $posts): array
    {
        return array_unique(array_column($posts, 'ip'));
    }

    public function findLastPostByIp(string $ip): ?Post
    {
        return $this->postRepository->findLastPostByIp($ip);
    }

    /**
     * @throws Exception
     */
    public function createPostFromForm(PostForm $postForm): Post
    {
        $post = new Post();
        $post->loadDataFromPostForm($postForm);

        if (!$this->postRepository->save($post)) {
            $errorsSummary = [];
            foreach ($post->getErrors() as $attribute => $errors) {
                $errorsSummary[$attribute] = implode(', ', $errors);
            }
            throw new ValidationException($errorsSummary);
        }

        return $post;
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function updatePost(int $postId, PostForm $postForm): bool
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
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function deletePost(int $postId): void
    {
        if (!$this->postRepository->delete($postId)) {
            throw new DomainException('Unable to delete post from database');
        }
    }

    /**
     * @throws \Exception
     */
    public function sendEmailSuccess(Post $post): void
    {
        $this->emailService->sendPostSaved($post);
    }
}