<?php

/**
* Главная страница приложения.
*
* @var View $this
* @var PostBaseForm $model
*/

use yii\web\View;
use app\models\Form\PostBaseForm;
?>

<div class="col-lg-4 mb-3">
    <?= $this->render('_form', [
        'model' => $model,
        'isEdit' => true,
        'action' => ['post/edit', 'postId' => $model->id],
    ]) ?>
</div>