<?php

namespace app\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\base\Event;

class SoftDeleteBehavior extends Behavior
{
    public string $deletedAttribute = 'deleted_at';
    public string $value;

    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_DELETE => 'softDelete',
        ];
    }

    /**
     * Обработчик события перед удалением.
     */
    public function softDelete(Event $event): void
    {
        if ($this->owner instanceof ActiveRecord) {
            $this->owner->{$this->deletedAttribute} = $this->value;
            $this->owner->updateAttributes([$this->deletedAttribute]);
            $event->isValid = false;
        }
    }

    /**
     * Метод для восстановления записи.
     */
    public function restore(): bool
    {
        if ($this->owner instanceof ActiveRecord) {
            $this->owner->{$this->deletedAttribute} = null;

            return $this->owner->updateAttributes([$this->deletedAttribute]);
        }

        return false;
    }
}