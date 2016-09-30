<?php

/*
 * HiSite Domain module
 *
 * @link      https://github.com/hiqdev/hipanel-domain-checker
 * @package   hipanel-domain-checker
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016, HiQDev (http://hiqdev.com/)
 */

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
        \Yii::setAlias('@domainchecker', '/domainchecker/domainchecker');
    }
}
