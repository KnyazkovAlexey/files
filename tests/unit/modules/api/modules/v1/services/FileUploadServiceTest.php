<?php

namespace tests\unit\modules\api\modules\v1\services;

use app\modules\api\modules\v1\services\FileUploadService;
use tests\unit\TestCase;
use yii\web\UploadedFile;
use app\modules\api\modules\v1\models\UploadedFile as UploadedFileModel;
use Exception;
use Throwable;

/**
 * Тестирование сервиса для загрузки файлов.
 */
class FileUploadServiceTest extends TestCase
{
    /** @inheritdoc */
    protected bool $useFiles = true;

    /**
     * Успешная загрузка файла.
     *
     * @throws Throwable
     */
    public function testSuccessfulUpload()
    {
        /** @var string $fileName */
        $fileName = 'Привет, мир.txt';
        /** @var string $preparedFileName */
        $preparedFileName = 'privet, mir.txt';

        /** @var UploadedFile $file */
        $file = $this->createUploadedFile(['name' => $fileName]);

        $this->createFileUploadService()->uploadFiles([$file]);

        $this->assertTrue(UploadedFileModel::find()->where(['original_name' => $preparedFileName])->exists());
    }

    /**
     * Загрузка файла с ненулевым кодом ошибки.
     * @see https://www.php.net/manual/en/features.file-upload.errors.php
     *
     * @throws Throwable
     */
    public function testUploadFileWithErrorCode()
    {
        /** @var UploadedFile $file */
        $file = $this->createUploadedFile(['error' => rand(1, 8)]);

        $this->expectException(Exception::class);

        $this->createFileUploadService()->uploadFiles([$file]);
    }

    /**
     * Создание сервиса для загрузки файлов.
     *
     * @return FileUploadService
     * @throws Exception
     */
    protected function createFileUploadService(): FileUploadService
    {
        $service = new FileUploadService();

        $service->setUploadsPath(self::TMP_DIR_PATH);

        return $service;
    }
}