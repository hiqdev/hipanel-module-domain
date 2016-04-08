<?php

/*
 * Domain checker plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-domain-checker
 * @package   hipanel-domain-checker
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domainchecker;

class Plugin extends \hiqdev\pluginmanager\Plugin
{
    protected $_items = [
        'aliases' => [
            '@domainchecker' => '/domainchecker/domainchecker',
        ],
        'modules' => [
            'domainchecker' => [
                'class' => 'hipanel\modules\domainchecker\Module',
            ],
        ],
        'components' => [
            'i18n' => [
                'translations' => [
                    'hipanel/domainchecker' => [
                        'class' => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@hipanel/modules/domainchecker/messages',
                        'fileMap' => [
                            'hipanel/domainchecker' => 'domainchecker.php',
                        ],
                    ],
                ],
            ],
        ],
    ];
}
