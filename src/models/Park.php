<?php

namespace hipanel\modules\domain\models;

use Yii;

class Park extends \hipanel\base\Model
{
    public function rules()
    {
        return [
            [['id', 'domain_id', 'dns_id', 'type_id'], 'integer'],
            [['title', 'siteheader', 'sitetext'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'tilte' => Yii::t('hipanel:domain', 'Page title'),
            'siteheader' => Yii::t('hipanel:domain', 'Page header text'),
            'sitetext' => Yii::t('hipanel:domain', 'Page text'),
            'type_id' => Yii::t('hipanel:domain', 'Page skin'),
        ];
    }
}
