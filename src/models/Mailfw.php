<?php

namespace hipanel\modules\domain\models;

use Yii;

class Mailfw extends \hipanel\base\Model
{
    use PaidFeatureForwardingTrait;

    public $status = 'new';

    public $typename = 'email';

    public function rules()
    {
        return [
            [['id', 'domain_id', 'dns_id', 'type_id'], 'integer'],
            [['name', 'value', 'type', 'type_label', 'status', 'typename'], 'string'],
            [['name', 'value'], 'required'],
            [['value'], 'email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('hipanel.domain.premium', 'User name'),
            'value' => Yii::t('hipanel.domain.premium', 'Forwarding addresses comma or space separeted'),
        ];
    }
}
