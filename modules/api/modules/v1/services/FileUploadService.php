<?php

namespace app\modules\api\modules\v1\services;

use yii\helpers\Inflector;
use yii\web\UploadedFile;
use app\modules\api\modules\v1\models\UploadedFile as UploadedFileModel;
use Exception;
use Throwable;
use Yii;

/**
 * Сервис для загрузки файлов.
 */
class FileUploadService
{
    /**
     * Путь до папки, в которую следует загружать файлы.
     */
    protected string $uploadsPath = '@app/uploads';

    /**
     * Смена папки для загрузки файлов.
     *
     * @param string $uploadsPath Абсолютный путь или алиас.
     * @return bool
     * @throws Exception
     */
    public function setUploadsPath(string $uploadsPath): bool
    {
        if (!is_dir(Yii::getAlias($uploadsPath))) {
            throw new Exception('Папка ' . $uploadsPath . ' не существует.');
        }

        $this->uploadsPath = $uploadsPath;

        return true;
    }

    /**
     * Загрузка файлов.
     * Загружаем в транзакции, то есть либо всё, либо ничего.
     *
     * @param UploadedFile[] $files
     * @return UploadedFileModel[]
     * @throws Throwable
     */
    public function uploadFiles(array $files): array
    {
        $transaction = Yii::$app->db->beginTransaction();

        /** @var UploadedFileModel[] */
        $models = [];

        try {
            foreach ($files as $file) {
                $models[] = $this->uploadFile($file);
            }

            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();

            throw $e;
        }

        return $models;
    }

    /**
     * Загрузка одного файла.
     *
     * @param UploadedFile $file
     * @return UploadedFileModel
     * @throws Exception
     */
    protected function uploadFile(UploadedFile $file): UploadedFileModel
    {
        /** @var string $filePath */
        $filePath = $this->generateFilePath($file);

        if (!$file->saveAs($filePath)) {
            throw new Exception('Не удалось загрузить файл ' . $file->name . '.');
        }

        /** @var UploadedFileModel $model */
        $model = new UploadedFileModel([
            'path' => $filePath,
            'original_name' => $this->prepareFileName($file->name),
            'uploaded_at' => date('Y-m-d H:i:s'),
        ]);

        if (!$model->save()) {
            throw new Exception('Не удалось сохранить в БД данные о файле ' . $file->name . '.');
        }

        return $model;
    }

    /**
     * Генерация пути для сохранения файла.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilePath(UploadedFile $file): string
    {
        /** @var string $fileName */
        $fileName = uniqid(more_entropy: true);

        if (!empty($file->extension)) {
            $fileName .= '.' . $file->extension;
        }

        return Yii::getAlias($this->uploadsPath . '/' . $fileName);
    }

    /**
     * Обработка наименования файла (транслит, нижний регистр).
     *
     * @param string $originalName
     * @return string
     */
    protected function prepareFileName(string $originalName): string
    {
        return Inflector::transliterate(mb_strtolower($originalName));
    }
}
