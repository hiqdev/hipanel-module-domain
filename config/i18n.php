<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

return [
    'languages' => ['ru'],
    'sourcePath' => dirname(__DIR__) . '/src',
    'messagePath' => dirname(__DIR__) . '/src/messages',
    'removeUnused' => false,
    'ignoreCategories' => ['hipanel:client', 'hipanel', 'cart'],
];
