<?php

namespace hipanel\modules\domain\forms;

use hipanel\modules\dns\validators\DomainPartValidator;
use Yii;

class BulkCheckForm extends CheckForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fqdn'], DomainPartValidator::class, 'enableIdn' => true, 'mutateAttribute' => false, 'message' => Yii::t('hipanel:domain', 'Domain name is invalid')],
            [['fqdn'], 'zoneIsAllowed'],
            [['fqdn'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fqdn' => Yii::t('hipanel:domain', 'Domains'),
            'zone' => Yii::t('hipanel:domain', 'Zones'),
        ];
    }
}
