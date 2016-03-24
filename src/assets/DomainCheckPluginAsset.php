<?php

/*
 * Domain checker plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-domain-checker
 * @package   hipanel-domain-checker
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domainchecker\assets;

use yii\web\AssetBundle;

class DomainCheckPluginAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@hipanel/modules/domainchecker/assets/DomainCheckPluginAssets';

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
