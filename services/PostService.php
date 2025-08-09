<?php

namespace app\services;

use app\models\Form\PostForm;
use app\models\Post;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

readonly class PostService
{
    public function __construct(private EmailService $emailService)
    {
    }

    /**
     * @throws NotFoundHttpException
     */
    public function findById(int $postId): Post
    {
        $post = Post::findOne($postId);
        if (!$post) {
            throw new NotFoundHttpException(Yii::t('app', 'post_not_exist'));
        }

        return $post;
    }

    public function findAll(): array
    {
        return Post::find()->all();
    }

    public function findUniqueIPs(array $posts): array
    {
        return array_unique(array_column($posts, 'ip'));
    }

    public function findCountPostsByIp(array $ip): array
    {
        $posts = Post::find(true)
            ->select(['ip', 'COUNT(id) AS count'])
            ->groupBy('ip')
            ->where(['in', 'ip', $ip])
            ->asArray()
            ->all();

        $ipCounts = [];
        foreach ($posts as $post) {
            $ipCounts[$post['ip']] = $post['count'];
        }

        return $ipCounts;
    }

    public function createPost(PostForm $postForm): ?Post
    {
        $post = new Post();
        $post->loadDataFromPostForm($postForm);

        if (!$post->save()) {
            $errorsSummary = [];
            foreach($post->errors as $key => $errors) {
                $errorsSummary[$key] = implode(', ', $errors);
            }
            Yii::$app->session->setFlash('error', $errorsSummary);

            return null;
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'post_save_success'));

        return $post;
    }

    public function updatePost(int $postId, PostForm $postForm): bool
    {
        $post = $this->findById($postId);
        $post->loadDataFromPostForm($postForm);

        if (!$post->save()) {
            $errorsSummary = [];
            foreach($post->errors as $key => $errors) {
                $errorsSummary[$key] = implode(', ', $errors);
            }
            Yii::$app->session->setFlash('error', $errorsSummary);

            return false;
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'post_save_success'));

        return true;
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function deletePost(int $postId): void
    {
        $post = $this->findById($postId);
        $post->delete();

        if ($post->isDelete() !== null) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'post_delete_success'));
        } else {
            Yii::$app->session->setFlash('error', implode(', ', $post->getErrors()));
        }
    }

    public function sendEmailSuccess(Post $post): void
    {
        try {
            $editUrl = Yii::$app->urlManager->createAbsoluteUrl(['post/update', 'postId' => $post->id]);
            $deleteUrl = Yii::$app->urlManager->createAbsoluteUrl(['post/delete', 'postId' => $post->id]);

            $message = "
                <p>" . Yii::t('app', 'post_email__saved_success') . "</p>
                <p><strong>" . Yii::t('app', 'post_email_author', ['name' => htmlspecialchars($post->name)]) . "</strong></p>
                <p><strong>" . Yii::t('app', 'post_email_message', ['description' => htmlspecialchars($post->description)]) . "</strong></p>
                <p>
                    <a href=\"{$editUrl}\">" . Yii::t('app', 'post_email_edit_post') . "</a> | 
                    <a href=\"{$deleteUrl}\" style=\"color:red;\">" . Yii::t('app', 'post_email_delete_post') . "</a>
                </p>
            ";

            $this->emailService->send(
                $post->email,
                Yii::t('app', 'post_email_subject'),
                $message
            );
        } catch(\Exception $e) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('app', 'post_email_error') . ' ' . $e->getMessage()
            );
        }
    }

    public function findLastPostByIp(string $ip): Post
    {
        return Post::find()
            ->where(['ip' => $ip])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
    }
}