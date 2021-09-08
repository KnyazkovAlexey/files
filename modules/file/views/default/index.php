<?php

use app\modules\file\assetBundles\FileAsset;
use yii\web\View;

/**
* @var $this View
*/

FileAsset::register($this);

$this->title = 'Файлы';
?>

<h1><?= $this->title ?></h1>

<div id="app"></div>
