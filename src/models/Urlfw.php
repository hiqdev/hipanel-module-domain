<?php

namespace hipanel\modules\domain\models;

use Yii;

class Urlfw extends \hipanel\base\Model
{
    use PaidFeatureForwardingTrait;

    public $status = 'not changed';

    public function rules()
    {
        return [
            [['id', 'domain_id', 'dns_id', 'type_id'], 'integer'],
            [['name', 'value', 'type', 'type_label', 'currentTab', 'status'], 'string'],
            [['name', 'type', 'value'], 'required'],
            [['value'], 'url'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('hipanel.domain.premium', 'Subdomain name'),
            'type_label' => Yii::t('hipanel.domain.premium', 'Record type'),
            'type' => Yii::t('hipanel.domain.premium', 'Record type'),
            'value' => Yii::t('hipanel.domain.premium', 'Forwarding address'),
        ];
    }

}
