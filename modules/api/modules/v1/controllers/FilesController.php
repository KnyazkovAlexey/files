<?php

namespace app\modules\api\modules\v1\controllers;

use app\modules\files\models\forms\UploadForm;
use app\modules\files\models\UploadedFile as UploadedFileModel;
use app\modules\files\services\FileUploadService;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use Yii;
use Throwable;

class FilesController extends ActiveController
{
    /**
     * @inheridoc
     */
    public $modelClass = UploadedFileModel::class;

    /**
     * @inheridoc
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @inheridoc
     */
    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'upload' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheridoc
     */
    public function actions(): array
    {
        return [
            'index' => parent::actions()['index'],
        ];
    }

    /**
     * Загрузка файлов на сервер.
     *
     * @return array
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function actionUpload(): array
    {
        $form = new UploadForm();

        $form->files = UploadedFile::getInstances($form, 'files');

        if (!$form->validate()) {
            throw new BadRequestHttpException($form->firstErrorMessage);
        }

        try {
            return (new FileUploadService())->uploadFiles($form->files);
        } catch (Throwable $e) {
            Yii::error($e->getMessage());

            throw new ServerErrorHttpException('Произошла ошибка.');
        }
    }
}