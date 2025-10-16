<?php

/**
 * Шаблон отображения ошибок.
 *
 * @var View $this
 * @var string $name Название ошибки
 * @var string $message Сообщение об ошибке
 * @var Exception $exception Объект исключения
 */

use yii\web\View;
use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.
    </p>

</div>
