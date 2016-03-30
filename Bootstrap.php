<?php

namespace hipanel\modules\domainchecker;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($application)
    {
        $application->getI18n()->translations['hipanel/domainchecker'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@hipanel/modules/domainchecker/messages',
            'fileMap' => [
                'hipanel/domainchecker' => 'domainchecker.php',
            ],
        ];
    }
}