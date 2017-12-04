<?php

namespace hipanel\modules\domain\models;

use Yii;

class Parking extends \hipanel\base\Model
{
    use PaidFeatureForwardingTrait;

    public function rules()
    {
        return [
            [['id', 'domain_id', 'dns_id', 'type_id', 'park_id'], 'integer'],
            [['title', 'siteheader', 'sitetext'], 'string'],
            [['title', 'siteheader', 'sitetext', 'type_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('hipanel.domain.premium', 'Title'),
            'siteheader' => Yii::t('hipanel.domain.premium', 'Page header text'),
            'sitetext' => Yii::t('hipanel.domain.premium', 'Page text'),
            'type_id' => Yii::t('hipanel.domain.premium', 'Page skin'),
        ];
    }

    public function skinOptions()
    {
        return [
            0 => Yii::t('hipanel.domain.premium', 'Turn off'),
            1 => Yii::t('hipanel.domain.premium', 'Design 1'),
            2 => Yii::t('hipanel.domain.premium', 'Design 2'),
            3 => Yii::t('hipanel.domain.premium', 'Design 3'),
        ];
    }

    public function getSkinImage()
    {
        if ($this->type_id) {
            return '//ahnames.com/www/img/parking/park' . $this->type_id . '.png';
        }

        return false;
    }
}

