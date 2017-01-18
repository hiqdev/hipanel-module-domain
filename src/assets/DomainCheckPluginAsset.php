<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\assets;

use yii\web\AssetBundle;

class DomainCheckPluginAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@hipanel/modules/domain/assets/DomainCheckPluginAssets';

    /**
     * @var array
     */
    public $js = [
        'js/DomainCheckPlugin.js',
    ];

    /**
     * @var array
     */
    public $depends = ['yii\web\JqueryAsset'];
}
