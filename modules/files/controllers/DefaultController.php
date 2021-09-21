<?php

namespace app\modules\files\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    /**
     * Файлы.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Добавление файлов.
     *
     * @return string
     */
    public function actionAdd(): string
    {
        return $this->render('index');
    }
}
