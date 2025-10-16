<?php

namespace app\models;

use app\components\SoftDeleteBehavior;
use app\models\Form\PostBaseForm;
use app\models\Query\PostQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Модель Post — сущность поста (сообщения).
 * Поддерживает soft delete и автоматические временные метки.
 */
class Post extends ActiveRecord
{

    /**
     * @inheritdoc
     * Поведения модели:
     * - SoftDeleteBehavior — мягкое удаление через поле deleted_at
     * - TimestampBehavior — установка created_at и updated_at
 *
     * @return array[]
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

    /**
     * Имя таблицы в БД.
     *
     * @return string
     */
    public static function tableName(): string
    {
        return 'post';
    }

    /**
     * Правила валидации для полей модели.
     *
     * @return array
     */
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
     * Используем свой кастомный запрос с фильтром soft delete.
     *
     * @return ActiveQuery
     */
    public static function find(): ActiveQuery
    {
        return new PostQuery(get_called_class());
    }

    /**
     * Заполняет модель данными из формы.
     * При редактировании не обновляет имя и email.
     *
     * @param PostBaseForm $postForm
     * @return void
     */
    public function loadDataFromPostForm(PostBaseForm $postForm): void
    {
        if (!$postForm->isEdit) {
            $this->name = $postForm->name;
            $this->email = $postForm->email;
        }
        $this->description = $postForm->description;
    }

    /**
     * Проверяет, помечен ли пост как удалённый.
     *
     * @return bool
     */
    public function isDelete(): bool
    {
        return $this->deleted_at !== null;
    }
}