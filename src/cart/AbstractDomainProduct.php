<?php

namespace hipanel\modules\domain\cart;

use hipanel\modules\finance\cart\AbstractCartPosition;
use Yii;

abstract class AbstractDomainProduct extends AbstractCartPosition
{
    public function getIcon()
    {
        return '<i class="fa fa-globe"></i>';
    }

    public function getQuantityOptions()
    {
        $result = [];
        for ($n=1;$n<11;$n++) {
            $result[$n] = Yii::t('app', '{0, plural, one{# year} other{# years}}', $n);
        }

        return $result;
    }
}
