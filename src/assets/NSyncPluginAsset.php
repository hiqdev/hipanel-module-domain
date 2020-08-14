<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\assets;

use yii\web\AssetBundle;

class NSyncPluginAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__ . '/NSyncPluginAssets';

    /**
     * @var array
     */
    public $js = [
        'js/NSyncPlugin.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'wbraganca\dynamicform\DynamicFormAsset',
    ];
}
