<?php

namespace app\modules\files\models;

use Yii;

/**
 * This is the model class for table "uploaded_file".
 *
 * @property int $id
 * @property string $path Путь к файлу
 * @property string $original_name Оригинальное наименование
 * @property string $uploaded_at Дата загрузки
 */
class UploadedFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'uploaded_file';
    }

    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['path', 'original_name', 'uploaded_at'], 'required'],
            [['uploaded_at'], 'safe'],
            [['path', 'original_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'path' => 'Путь к файлу',
            'original_name' => 'Оригинальное наименование',
            'uploaded_at' => 'Дата загрузки',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields(): array
    {
        return [
            'original_name',
            'uploaded_at',
        ];
    }
}
