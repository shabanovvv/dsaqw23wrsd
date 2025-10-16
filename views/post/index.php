<?php

/**
 * Главная страница приложения.
 *
 * @var View $this
 * @var ActiveForm $form
 * @var PostBaseForm $model
 * @var Pagination $pagination
 * @var Post[] $posts
 * @var array $ipCounts
 */

use yii\web\View;
use yii\bootstrap5\ActiveForm;
use app\models\Form\PostBaseForm;
use app\models\Post;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <h3>Сообщения</h3>
                        <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                                <?= $this->render('_post', [
                                    'post' => $post,
                                    'ipCounts' => $ipCounts,
                                ]) ?>
                            <?php endforeach; ?>
                            <?= LinkPager::widget(['pagination' => $pagination]) ?>
                        <?php else: ?>
                            <p>Нет сообщений.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <?= $this->render('_form', [
                    'model' => $model,
                    'isEdit' => false,
                    'action' => ['post/create'],
                ]) ?>
            </div>
        </div>

    </div>
</div>