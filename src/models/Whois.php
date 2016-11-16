<?php

namespace hipanel\modules\domain\models;

use hipanel\validators\IdnValidator;
use hiqdev\hiart\ActiveRecord;
use Yii;

/**
 * Class Whois represents WHOIS request for the domain
 *
 * @property string $domain
 * @package hipanel\modules\domain\models
 */
class Whois extends ActiveRecord
{
    public function rules()
    {
        return [
            [['domain'], IdnValidator::class],
        ];
    }

    public function attributes()
    {
        return [
            'domain', 'registrar', 'nss', 'created', 'updated',
            'expires', 'rawdata', 'ip', 'country_name', 'city',
            'available',
            'unsupported',
        ];
    }

    public function getDomainAsUtf8()
    {
        return (new IdnValidator())->convertAsciiToIdn($this->domain);
    }

    public function attributeLabels()
    {
        return [
            'domain' => Yii::t('hipanel', 'Domain'),
            'created' => Yii::t('hipanel:domain', 'Registered'),
            'updated' => Yii::t('hipanel:domain', 'Updated'),
            'expires' => Yii::t('hipanel:domain', 'Expires'),
            'registrar' => Yii::t('hipanel:domain', 'Registrar'),
            'nss' => Yii::t('hipanel:domain', 'Name servers'),
            'ip' => Yii::t('hipanel:domain', 'IP'),
            'country_name' => Yii::t('hipanel:domain', 'Country'),
            'city' => Yii::t('hipanel:domain', 'City'),
        ];
    }

    public function getScreenshot()
    {
        return sprintf('//mini.s-shot.ru/1920x1200/JPEG/520/Z100/?%s', $this->domain);
    }
}

