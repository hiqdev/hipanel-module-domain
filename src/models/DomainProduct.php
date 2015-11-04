<?php

namespace hipanel\modules\domain\models;

use hipanel\models\CartPosition;
use Yii;

class DomainProduct extends CartPosition
{
    /**
     * @var Domain
     */
    public $model;

    public $icon = '<i class="fa fa-globe"></i>'; //'<i class="fa fa-server text-muted"></i>';

    public function getQuantityOptions()
    {
        $result = [];
        foreach ([1,2,3,4,5,6,7,8,9,10] as $n) {
            $result[$n] = Yii::t('app', '{0, plural, one{# year} other{# years}}', $n);
        }

        return $result;
    }
}