<?php

namespace app\filters;

use app\models\Post;
use Yii;
use yii\base\ActionFilter;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class DeletePostLimitFilter extends ActionFilter
{
    /**
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function beforeAction($action): bool
    {
        $postId = Yii::$app->request->get('postId');

        if ($postId === null) {
            throw new NotFoundHttpException('Post ID не указан.');
        }

        $post = Post::findOne($postId);
        if ($post === null) {
            throw new NotFoundHttpException('Пост не найден.');
        }

        $limitSec = 14 * 24 * 3600; // 14 дней
        $expiry = $post->created_at + $limitSec;

        if (time() > $expiry) {
            Yii::$app->response->getHeaders()->set('X-Delete-Allowed-Until', gmdate('D, d M Y H:i:s', $expiry) . ' GMT');

            throw new ForbiddenHttpException('Удаление доступно только в течение 14 дней после публикации.');
        }

        return parent::beforeAction($action);
    }
}