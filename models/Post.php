<?php

namespace app\models;

use app\components\SoftDeleteBehavior;
use app\models\Form\PostForm;
use app\models\Query\PostQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    public bool $isEdit = false;

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
            ...PostCommonRules::rules(),
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

    public function loadDataFromPostForm(PostForm $postForm): void
    {
        if (!$postForm->isEdit) {
            $this->name = $postForm->name;
            $this->email = $postForm->email;
        }
        $this->description = $postForm->description;
        $this->isEdit = $postForm->isEdit;
        $this->ip = Yii::$app->request->getUserIP();
    }

    public function isDelete(): bool
    {
        return $this->deleted_at !== null;
    }
}