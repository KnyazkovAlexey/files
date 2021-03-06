<?php

namespace app\controllers;

use app\models\forms\UploadForm;
use app\services\FileUploadService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Throwable;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\UploadedFile as UploadedFileModel;

/**
 * Контроллер для работы с файлами.
 * 
 * Class FilesController
 * @package app\controllers
 */
class FilesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'upload' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Страница со списком загруженных файлов.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = new ActiveDataProvider([
            'query' => UploadedFileModel::find(),
            'pagination' => [
                'pageSize' => 8,
            ],
        ]);

        return $this->render('index', compact('dataProvider'));
    }

    /**
     * Страница загрузки файлов.
     * 
     * @return string
     */
    public function actionAdd(): string
    {
        /** @var UploadForm $form */
        $form = new UploadForm();

        return $this->render('add', ['model' => $form]);
    }

    /**
     * Загрузка файлов.
     * 
     * @return string
     */
    public function actionUpload(): Response
    {
        /** @var UploadForm $form */
        $form = new UploadForm();

        $form->files = UploadedFile::getInstances($form, 'files');

        if (!$form->validate()) {
            Yii::$app->session->setFlash('error', Yii::t('app', $form->firstErrorMessage));
        } else {
            try {
                (new FileUploadService())->upload($form);

                Yii::$app->session->setFlash('success', Yii::t('app', 'Ок'));
            } catch (Throwable $e) {
                Yii::error($e->getMessage());

                Yii::$app->session->setFlash('error', Yii::t('app',
                    'Возникла ошибка. Обратитесь в техподдержку: ' . Yii::$app->params['adminEmail'] . '.'));
            }
        }

        return Yii::$app->getResponse()->redirect('add');
    }
}
