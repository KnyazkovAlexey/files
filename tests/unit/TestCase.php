<?php

namespace tests\unit;

use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;
use yii\helpers\FileHelper;
use Yii;
use yii\db\Transaction;
use Exception;
use yii\web\UploadedFile;
use yii\base\ErrorException;

/**
 * Базовый класс для юнит-тестов.
 *
 * Class TestCase
 * @package tests\unit
 */
class TestCase extends Unit
{
    /** @var string Путь до папки с временными файлами тестов. */
    protected const TMP_DIR_PATH = '@tests/tmp';

    /** @var Generator $faker Объект для генерации случайных данных. */
    public Generator $faker;

    /** @var bool $useDb Флаг о том, что тесты работают с БД. */
    protected bool $useDb = true;

    /** @var bool $useFiles Флаг о том, что тесты работают с файлами. */
    protected bool $useFiles = false;

    /** @var Transaction|null $transaction */
    protected ?Transaction $transaction = null;

    /**
     * @inheridoc
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Factory::create();
    }

    /**
     * @inheritDoc
     */
    public function _before(): void
    {
        parent::_before();

        if ($this->useDb) {
            $this->transaction = Yii::$app->db->beginTransaction();
        }
    }

    /**
     * @inheritDoc
     * @throws ErrorException
     */
    protected function _after()
    {
        parent::_after();

        if ($this->useDb) {
            $this->transaction->rollback();
        }

        if ($this->useFiles) {
            $this->clearTmpDir();
        }
    }

    /**
     * Очистка папки с временными файлами.
     *
     * @return bool
     * @throws ErrorException
     */
    protected function clearTmpDir(): bool
    {
        $tmpDirPath = Yii::getAlias(self::TMP_DIR_PATH);

        /** @var string[] */
        $dirs = array_filter(
            glob($tmpDirPath . DIRECTORY_SEPARATOR . '*'),
            'is_dir'
        );

        //Удаляем папки
        foreach ($dirs as $dirPath) {
            FileHelper::removeDirectory($dirPath);
        }

        //Удаляем файлы
        foreach (FileHelper::findFiles($tmpDirPath, ['except' => ['.gitignore']]) as $filePath) {
            unlink($filePath);
        }

        return true;
    }

    /**
     * Создание загруженного файла (имитация загрузки файла на сервер).
     *
     * @param array $attributes Кастомные свойства файла (name, type, tempName, size, error).
     * @return UploadedFile
     * @throws Exception
     */
    protected function createUploadedFile(array $attributes = []): UploadedFile
    {
        /** @var string $fileName */
        $fileName = $attributes['name'] ?? $this->faker->lexify('??????') .'.txt';

        /** @var string $filePath */
        $filePath = Yii::getAlias(self::TMP_DIR_PATH . '/' . $fileName);

        if (false === file_put_contents($filePath, 'test')) {
            throw new Exception('Не удалось создать файл ' . $filePath);
        }

        /** @var array $attributes */
        $attributes = array_merge([
            'name' => $fileName,
            'tempName' => $filePath,
            'type' => 'text/plain',
            'size' => 5,
            'error' => 0,
        ], $attributes);

        return new UploadedFile($attributes);
    }
}
