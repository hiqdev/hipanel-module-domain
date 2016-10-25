<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\models;

use Yii;

class HostSearch extends Host
{
    use \hipanel\base\SearchModelTrait;

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'domain_like' => Yii::t('hipanel/domain', 'Domain name'),
            'host_like' => Yii::t('hipanel/domain', 'Name server'),
        ]);
    }
}
