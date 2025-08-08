
<div class="col-lg-4 mb-3">
    <?= $this->render('_form', [
        'model' => $model,
        'action' => ['post/edit', 'postId' => $model->id],
    ]) ?>
</div>