<?php

namespace app\services;

use app\models\Post;
use Exception;
use Yii;
use yii\web\UrlManager;

/**
 * Сервис для отправки уведомлений по email.
 * Отвечает за формирование и отправку писем пользователям.
 */
readonly class EmailService
{
    public function __construct(private UrlManager $urlManager)
    {
    }

    /**
     * Отправляет письмо после успешного создания поста.
     * В шаблон передаются ссылки на редактирование и удаление.
     *
     * @param Post $post
     * @return void
     * @throws Exception
     */
    public function sendPostSaved(Post $post): void
    {
        // Генерация ссылок для действий с постом
        $editUrl = $this->urlManager->createAbsoluteUrl([
            'post/update',
            'postId' => $post->id
        ]);
        $confirmDeleteUrl = $this->urlManager->createAbsoluteUrl([
            'post/delete-confirm',
            'postId' => $post->id,
        ]);

        // Рендеринг HTML-шаблона письма
        $htmlBody = Yii::$app->view->renderFile('@app/mail/post_saved.php', [
            'post' => $post,
            'editUrl' => $editUrl,
            'confirmDeleteUrl' => $confirmDeleteUrl,
        ]);

        // Отправка письма пользователю
        $this->send(
            $post->email,
            Yii::t('app', 'post_email_subject'),
            $htmlBody
        );
    }

    /**
     * Отправляет email-сообщение.
     *
     * @param string $emailTo
     * @param string $subject
     * @param string $message
     * @return void
     * @throws Exception
     */
    public function send(string $emailTo, string $subject, string $message): void
    {
        try {
            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($emailTo)
                ->setSubject($subject)
                ->setHtmlBody($message)
                ->send();
        } catch (Exception $e) {
            throw new Exception('Ошибка при отправке письма: ' . $e->getMessage());
        }
    }
}