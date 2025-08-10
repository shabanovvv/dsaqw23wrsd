
<p><?= Yii::t('app', 'post_email__saved_success') ?></p>
<p><strong><?= Yii::t('app', 'post_email_author', ['name' => \yii\helpers\Html::encode($post->name)]) ?></strong></p>
<p><?= \yii\helpers\Html::encode($post->description) ?></p>
<p>
    <a href="<?= $editUrl ?>"><?= Yii::t('app', 'post_email_edit_post') ?></a>
    |
    <a href="<?= $confirmDeleteUrl ?>" style="color:red;"><?= Yii::t('app', 'post_email_delete_post') ?></a>
</p>