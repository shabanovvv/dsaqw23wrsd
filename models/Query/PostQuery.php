<?php

namespace app\models\Query;

use yii\db\ActiveQuery;

/**
 * Кастомный запрос для модели Post.
 * Добавляет автоматическую фильтрацию по soft delete.
 */
class PostQuery extends ActiveQuery
{
    /**
     *  Инициализация запроса.
     *  По умолчанию исключает записи с deleted_at != null.
     *
     * @return void
     */
    public function init(): void
    {
        parent::init();
        $this->andWhere(['deleted_at' => null]);
    }

    /**
     * Возвращает записи, отмеченные как удалённые (soft delete).
     *
     * @return self
     */
    public function withoutSoftDelete(): self
    {
        return $this->andWhere(['not', ['deleted_at' => null]]);
    }
}