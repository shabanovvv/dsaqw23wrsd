<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \app\models\Form\PostForm $model */

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
                        <?php else: ?>
                            <p>Нет сообщений.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <?= $this->render('_form', [
                    'model' => $model,
                    'action' => ['post/create'],
                ]) ?>
            </div>
        </div>

    </div>
</div>