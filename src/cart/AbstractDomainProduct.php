<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (http://hiqdev.com/)
 */

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
        for ($n = 1;$n < 11;++$n) {
            $result[$n] = Yii::t('app', '{0, plural, one{# year} other{# years}}', $n);
        }

        return $result;
    }
}
