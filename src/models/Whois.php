<?php

namespace hipanel\modules\domain\models;

use hipanel\validators\DomainValidator;
use hiqdev\hiart\ActiveRecord;
use Yii;

class Whois extends ActiveRecord
{
    public function rules()
    {
        return [
            [['domain'], DomainValidator::class],
        ];
    }

    public function attributes()
    {
        return [
            'domain', 'registrar', 'nss', 'created', 'updated',
            'expires', 'rawdata', 'ip', 'country_name', 'city',
            'available',
        ];
    }

    public function attributeLabels()
    {
        return [
            'domain' => Yii::t('hipanel', 'Domain'),
            'created' => Yii::t('hipanel/domain', 'Created'),
            'updated' => Yii::t('hipanel/domain', 'Updated'),
            'expires' => Yii::t('hipanel/domain', 'Expires'),
            'registrar' => Yii::t('hipanel/domain', 'Registrar'),
            'nss' => Yii::t('hipanel/domain', 'Name servers'),
            'ip' => Yii::t('hipanel/domain', 'IP'),
            'country_name' => Yii::t('hipanel/domain', 'Country'),
            'city' => Yii::t('hipanel/domain', 'City'),
        ];
    }

    public function getScreenshot()
    {
        return sprintf('//mini.s-shot.ru/1920x1200/JPEG/520/Z100/?%s', $this->domain);
    }
}

