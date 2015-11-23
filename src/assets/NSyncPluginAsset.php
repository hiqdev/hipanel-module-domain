<?php

namespace hipanel\modules\domain\assets;

use yii\web\AssetBundle;

class NSyncPluginAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@hipanel/modules/domain/assets';

    /**
     * @var array
     */
    public $js = [
        'js/NSyncPluginAsset.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'wbraganca\dynamicform\DynamicFormAsset',
    ];
}
