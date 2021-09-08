<?php

namespace app\modules\file\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
