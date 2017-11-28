<?php

namespace hipanel\modules\domain\models;

use Yii;

class Mailfw extends \hipanel\base\Model
{
    use PaidFeatureForwardingTrait;

    public function rules()
    {
        return [
            [['id', 'domain_id', 'dns_id', 'type_id'], 'integer'],
            [['name', 'value', 'type', 'type_label', 'status'], 'string'],
            [['name', 'value'], 'required'],
            [['value'], 'email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('hipanel:domain', 'User name'),
            'value' => Yii::t('hipanel:domain', 'Forwarding addresses'),
        ];
    }
}
