<?php

namespace app\models;

use app\components\SoftDeleteBehavior;
use app\models\Form\PostBaseForm;
use app\models\Query\PostQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
                'value' => time(),
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
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
            ['email', 'email'],
            ['name', 'string', 'min' => 2, 'max' => 15],
            ['description', 'trim'],
            ['description', 'string', 'min' => 5, 'max' => 255],
            [['ip'], 'ip'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find(): ActiveQuery
    {
        return new PostQuery(get_called_class());
    }

    public function loadDataFromPostForm(PostBaseForm $postForm): void
    {
        if (!$postForm->isEdit) {
            $this->name = $postForm->name;
            $this->email = $postForm->email;
        }
        $this->description = $postForm->description;
    }

    public function isDelete(): bool
    {
        return $this->deleted_at !== null;
    }
}