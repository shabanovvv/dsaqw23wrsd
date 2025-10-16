<?php
/**
 * Форма создания или редактирования поста.
 *
 * @var View $this
 * @var ActiveForm $form
 * @var PostBaseForm $model
 * @var array $action Маршрут для отправки формы
 * @var bool $isEdit Флаг режима редактирования
 */

use yii\web\View;
use yii\bootstrap5\ActiveForm;
use app\models\Form\PostBaseForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$form = ActiveForm::begin([
    'action' => $action,
    'id' => 'post-form'
]); ?>

<?php if (!$isEdit): ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'email')->textInput() ?>
<?php else: ?>
    <div class="form-group">
        <label for="postform-name">Имя</label>
        <div class="form-control-plaintext"><?= Html::encode($model->name) ?></div>
    </div>
    <div class="form-group">
        <label for="postform-email">Электронная почта</label>
        <div class="form-control-plaintext"><?= Html::encode($model->email) ?></div>
    </div>
<?php endif; ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
<?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
    'captchaAction' => 'post/captcha',
    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
]) ?>
<div class="form-group">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
</div>

<?php ActiveForm::end(); ?>