<?php

namespace app\filters;

use app\models\Post;
use app\services\PostService;
use HttpException;
use Yii;
use yii\base\ActionFilter;
use yii\web\TooManyRequestsHttpException;

/**
 * Фильтр ограничивает частоту создания постов.
 * Не позволяет отправлять сообщения чаще, чем раз в TIME_LIMIT секунд.
 */
class CreatePostLimitFilter extends ActionFilter
{
    const TIME_LIMIT = 180;

    public PostService $postService;

    /**
     * Проверяет, прошло ли достаточно времени с последнего поста пользователя.
     *
     * @param $action
     * @return bool
     * @throws TooManyRequestsHttpException
     * @throws HttpException
     */
    public function beforeAction($action): bool
    {
        $lastPost = $this->postService->findLastPostByIp(Yii::$app->request->getUserIP());

        if ($lastPost !== null) {
            $timeDiff = time() - $lastPost->created_at;

            if ($timeDiff < self::TIME_LIMIT) {
                $retryAfter = self::TIME_LIMIT - $timeDiff;
                $nextPostTime = date('Y-m-d H:i:s', $lastPost->created_at + self::TIME_LIMIT);
                $message = "Вы можете отправить новое сообщение после {$nextPostTime}";

                Yii::$app->response->getHeaders()->set('Retry-After', $retryAfter);

                if (class_exists(TooManyRequestsHttpException::class)) {
                    throw new TooManyRequestsHttpException($message);
                }

                throw new HttpException(429, $message);
            }
        }

        return parent::beforeAction($action);
    }
}