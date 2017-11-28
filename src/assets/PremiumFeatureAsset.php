<?php
/**
 * Hosting Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-hosting
 * @package   hipanel-module-hosting
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\assets;

use hiqdev\yii2\assets\select2\Select2Asset;
use yii\web\AssetBundle;

class PremiumFeatureAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@hipanel/modules/domain/assets/PremiumFeaturesAssets';

    /**
     * @var array
     */
    public $depends = [
        Select2Asset::class,
    ];
}
