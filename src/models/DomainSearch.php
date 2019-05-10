<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;
use Yii;

class DomainSearch extends Domain
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'created_from', 'created_till', 'with_nsips', 'client_like', 'contacts', 'emails',
        ]);
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'domains' => Yii::t('hipanel:domain', 'Domain names (one per row)'),
            'emails' => Yii::t('hipanel:domain', 'Emails (one per row)'),
        ]);
    }
}
