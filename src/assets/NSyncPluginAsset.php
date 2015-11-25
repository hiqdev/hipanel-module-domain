<?php

namespace hipanel\modules\domain\assets;

use yii\web\AssetBundle;

class NSyncPluginAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@hipanel/modules/domain/assets/NSyncPluginAssets';

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
