<?php

namespace app\services;

use app\models\Post;
use Yii;
use yii\web\UrlManager;

readonly class EmailService
{
    public function __construct(private UrlManager $urlManager)
    {
    }

    /**
     * @throws \Exception
     */
    public function sendPostSaved(Post $post): void
    {
        $editUrl = $this->urlManager->createAbsoluteUrl([
            'post/update',
            'postId' => $post->id
        ]);
        $confirmDeleteUrl = $this->urlManager->createAbsoluteUrl([
            'post/delete-confirm',
            'postId' => $post->id,
        ]);

        $htmlBody = Yii::$app->view->renderFile('@app/mail/post_saved.php', [
            'post' => $post,
            'editUrl' => $editUrl,
            'confirmDeleteUrl' => $confirmDeleteUrl,
        ]);

        $this->send(
            $post->email,
            Yii::t('app', 'post_email_subject'),
            $htmlBody
        );
    }

    public function send(string $emailTo, string $subject, string $message): void
    {
        try {
            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($emailTo)
                ->setSubject($subject)
                ->setHtmlBody($message)
                ->send();
        } catch (\Exception $e) {
            throw new \Exception('Ошибка при отправке письма: ' . $e->getMessage());
        }
    }
}