<?php

/*
 * Domain checker plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-domain-checker
 * @package   hipanel-domain-checker
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

return [
    'aliases' => [
        '@domainchecker' => '/domainchecker/domainchecker',
    ],
    'modules' => [
        'domainchecker' => [
            'class' => \hipanel\modules\domainchecker\Module::class,
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'hipanel/domainchecker' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/domainchecker/messages',
                    'fileMap' => [
                        'hipanel/domainchecker' => 'domainchecker.php',
                    ],
                ],
            ],
        ],
    ],
];
