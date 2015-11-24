<?php

namespace hipanel\modules\domain\models;

use hipanel\base\Model;
use hipanel\modules\dns\validators\DomainPartValidator;
use Yii;

class Ns extends Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['name', 'ip'], 'filter', 'filter' => 'trim'],
            [['name'],  DomainPartValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'name' => Yii::t('app', 'Name server'),
            'ip' => Yii::t('app', 'IP'),
        ]);
    }
}