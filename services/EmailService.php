<?php

namespace app\services;

use Yii;

class EmailService
{
    public function __construct()
    {
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