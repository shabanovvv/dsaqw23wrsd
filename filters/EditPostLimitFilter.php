<?php

namespace app\filters;

use app\models\Post;
use Yii;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Фильтр ограничивает редактирование поста.
 * Разрешено только в течение 12 часов после создания.
 */
class EditPostLimitFilter extends ActionFilter
{
    /**
     * Проверяет, можно ли редактировать пост.
     *
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function beforeAction($action): bool
    {
        $postId = Yii::$app->request->get('postId');
        $post = Post::findOne($postId);
        if ($post === null) {
            throw new NotFoundHttpException('Пост не найден.');
        }

        $limitSec = 12 * 3600;
        $expiry = $post->created_at + $limitSec;

        if (time() > $expiry) {
            Yii::$app->response->getHeaders()->set('X-Edit-Allowed-Until', gmdate('D, d M Y H:i:s', $expiry) . ' GMT');

            throw new ForbiddenHttpException('Редактирование доступно только в течение 12 часов после отправки сообщения.');
        }

        return parent::beforeAction($action);
    }
}