<?php

use app\components\TextHelper;

?>
<div class="card card-default mb-2">
    <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($post->name) ?></h5>
        <p><?= htmlspecialchars($post->description) ?></p>
        <p>
            <small class="text-muted">
                <?= Yii::$app->formatter->asRelativeTime($post->created_at) ?> |
                <?= TextHelper::hideIp($post->ip) ?> |
                <?= TextHelper::pluralForm(
                    isset($ipCounts[$post->ip]) ? $ipCounts[$post->ip] : 0,
                    ['пост', 'поста', 'постов']
                ) ?>
            </small>
        </p>
    </div>
</div>