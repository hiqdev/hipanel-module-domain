<?php

namespace hipanel\modules\domainchecker\models;

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
        ];
    }

    public function attributeLabels()
    {
        return [
            'domain' => Yii::t('hipanel', 'Domain'),
            'created' => Yii::t('hipanel/domainchecker', 'Created'),
            'updated' => Yii::t('hipanel/domainchecker', 'Updated'),
            'expires' => Yii::t('hipanel/domainchecker', 'Expires'),
            'registrar' => Yii::t('hipanel/domainchecker', 'Registrar'),
            'nss' => Yii::t('hipanel/domainchecker', 'Name servers'),
            'ip' => Yii::t('hipanel/domainchecker', 'IP'),
            'country_name' => Yii::t('hipanel/domainchecker', 'Country'),
            'city' => Yii::t('hipanel/domainchecker', 'City'),
        ];
    }

    public function getScreenshot()
    {
        return sprintf('//mini.s-shot.ru/1920x1200/JPEG/520/Z100/?%s', $this->domain);
    }
}

