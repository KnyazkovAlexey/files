<?php

namespace app\modules\files;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\files\controllers';

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->urlManager->addRules(require(__DIR__ . '/config/routes.php'), false);
    }
}