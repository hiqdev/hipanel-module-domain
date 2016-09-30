<?php

namespace hipanel\modules\domain\assets;

use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class WhoisAsset extends AssetBundle
{
    public $sourcePath = '@hipanel/modules/domain/assets/WhoisPluginAssets';

    public $js = [
        'WhoisPlugin.js',
    ];

    public $depends = [
        JqueryAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
    ];
}
