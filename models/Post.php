<?php

namespace app\models;

use app\components\SoftDeleteBehavior;

use \yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'softDelete' => [
                'class' => SoftDeleteBehavior::class,
                'deletedAttribute' => 'deleted_at',
                'value' => date('Y-m-d H:i:s'),
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    public static function tableName(): string
    {
        return 'post';
    }

    public function rules(): array
    {
        return [
            [['name', 'email', 'description'], 'required'],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find($modeSoftDelete = true): ActiveQuery
    {
        $query = parent::find();
        if ($modeSoftDelete) {
            $query->andWhere(['deleted_at' => null]);
        }

        return $query;
    }
}