<?php

namespace app\modules\files\assetBundles;

use yii\web\AssetBundle;

class FilesAsset extends AssetBundle
{
    /**
     * @inheridoc
     */
    public $sourcePath = '@app/modules/files/assets';

    /**
     * @inheridoc
     */
    public $js = [
        'js/app.js',
    ];

    /**
     * @inheridoc
     */
    public $css = [
        'css/app.css',
    ];
}