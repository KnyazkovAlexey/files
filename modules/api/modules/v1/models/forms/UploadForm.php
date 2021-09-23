<?php

namespace app\modules\api\modules\v1\models\forms;

use app\behaviors\FirstErrorBehavior;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Форма для загрузки файлов.
 *
 * @property string|null $firstErrorMessage Первая ошибка валидации
 */
class UploadForm extends Model
{
    /** @var UploadedFile[] $files */
    public array $files = [];

    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return [
            'firstError' => [
                'class' => FirstErrorBehavior::class,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 5, 'maxSize' => 16 * 1024 * 1024,
                'extensions' => 'png, jpg, jpeg, gif, mp3, mp4, txt, pdf, docx, xlsx, odt, ods, zip',
                'uploadRequired' => 'Выберите файлы.',
                'tooMany' => 'Вы не можете загружать более 5 файлов.',
                'tooBig' => 'Размер файла не должен превышать 16MB.',
            ],
        ];
    }
}