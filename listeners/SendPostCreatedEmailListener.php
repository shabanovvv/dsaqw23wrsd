<?php

namespace app\listeners;

use app\events\PostCreatedEvent;
use app\services\EmailService;
use Throwable;
use Yii;

/**
 * Слушатель события PostCreatedEvent.
 * Отправляет письмо пользователю после успешного создания поста.
 */
readonly class SendPostCreatedEmailListener
{
    public function __construct(
        private EmailService $emailService
    ) {}

    /**
     * Обрабатывает событие PostCreatedEvent и инициирует отправку письма.
     */
    public function handle(PostCreatedEvent $event): void
    {
        try {
            $this->emailService->sendPostSaved($event->post);
            Yii::info('Письмо успешно отправлено для поста ID=' . $event->post->id, __METHOD__);
        } catch (Throwable $e) {
            Yii::error('Ошибка при отправке письма: ' . $e->getMessage(), __METHOD__);
        }
    }
}