<?php

namespace hipanel\modules\domain\models;

use Yii;

class MailFw extends \hipanel\base\Model
{
    public function rules()
    {
        return [
            [['id', 'domain_id', 'dns_id', 'type_id'], 'integer'],
            [['name', 'value', 'type', 'type_label'], 'string'],
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
