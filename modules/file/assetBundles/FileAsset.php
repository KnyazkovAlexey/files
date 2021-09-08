<?php

namespace app\modules\file\assetBundles;

use yii\web\AssetBundle;

class FileAsset extends AssetBundle
{
    /**
     * @inheridoc
     */
    public $sourcePath = '@app/modules/file/assets';

    /**
     * @inheridoc
     */
    public $js = [
        'js/app.js',
    ];
}