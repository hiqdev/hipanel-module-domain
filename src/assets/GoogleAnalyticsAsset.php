<?php

namespace hipanel\modules\domain\assets;

use yii\web\AssetBundle;

class GoogleAnalyticsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@hipanel/modules/domain/assets/GoogleAnalyticsAssets';

    /**
     * @var array
     */
    public $js = [
        'js/GoogleAnalytics.js',
    ];

    /**
     * @var array
     */
    public $depends = ['yii\web\JqueryAsset'];
}
