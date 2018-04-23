<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\models;

use Exception;
use hipanel\validators\DomainValidator;
use hiqdev\hiart\ActiveRecord;
use SEOstats\SEOstats;
use SEOstats\Services\Alexa;
use Yii;

/**
 * Class Whois represents WHOIS request for the domain.
 *
 * @property string $domain
 */
class Whois extends ActiveRecord
{
    const REGISTRATION_AVAILABLE = 'available';
    const REGISTRATION_UNAVAILABLE = 'unavailable';
    const REGISTRATION_UNSUPPORTED = 'unsupported';

    public function rules()
    {
        return [
            [['domain'], 'filter', 'filter' => 'strtolower'],
            [['domain'], DomainValidator::class, 'enableIdn' => true],
        ];
    }

    public function attributes()
    {
        return [
            'domain', 'registrar', 'nss', 'created', 'updated',
            'expires', 'rawdata', 'ip', 'country_name', 'city',
            'availability',
        ];
    }

    public function getDomainAsUtf8()
    {
        return (new DomainValidator())->convertAsciiToIdn($this->domain);
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
            'google' => Yii::t('hipanel:domain', 'Google PR'),
            'alexa' => Yii::t('hipanel:domain', 'Alexa Rank'),
            'yandex' => Yii::t('hipanel:domain', 'Yandex TIC'),
        ];
    }

    public function getScreenshot()
    {
        return sprintf('//mini.s-shot.ru/1920x1200/JPEG/520/Z100/?%s', $this->domain);
    }

    public function getUrl()
    {
        return 'http://' . $this->domain;
    }

    public function getGoogle()
    {
        try {
            $result = intval(\SEOstats\Services\Google::getPageRank($this->url));
            return $result;
        } catch (Exception $e) {

        }

        return null;
    }

    public function getAlexa()
    {
        try {
            $seostats = new SEOstats();
            if ($seostats->setUrl($this->url)) {
                return Alexa::getGlobalRank();
            }
        } catch (Exception $e) {

        }

        return null;
    }

    public function getYandex()
    {
        try {
            $str = file('http://bar-navig.yandex.ru/u?ver=2&show=32&url=' . $this->url);
            if ($str === false) {
                $ans = false;
            } else {
                $is_find = preg_match("/value=\"(.\d*)\"/", implode('', $str), $tic);

                if ($is_find < 1) {
                    $ans = 0;
                } else {
                    $ans = $tic[1];
                }
            }

            return $ans;
        } catch (Exception $e) {

        }

        return null;
    }
}
