<?php

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