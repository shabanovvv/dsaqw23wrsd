<?php

namespace app\models\Query;

use yii\db\ActiveQuery;

class PostQuery extends ActiveQuery
{
    public function softDelete(): self
    {
        $this->andWhere(['deleted_at' => null]);

        return $this;
    }

    public function all($db = null): array
    {
        $this->softDelete();

        return parent::all($db);
    }

    public function one($db = null)
    {
        $this->softDelete();

        return parent::one($db);
    }
}