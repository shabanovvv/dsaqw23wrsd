<?php

namespace app\listeners;

use app\events\PostCreatedEvent;
use app\services\EmailService;
use Yii;

readonly class SendPostCreatedEmailListener
{
    public function __construct(
        private EmailService $emailService
    ) {}

    public function handle(PostCreatedEvent $event): void
    {
        try {
            $this->emailService->sendPostSaved($event->post);
            Yii::info('Письмо успешно отправлено для поста ID=' . $event->post->id, __METHOD__);
        } catch (\Throwable $e) {
            Yii::error('Ошибка при отправке письма: ' . $e->getMessage(), __METHOD__);
        }
    }
}