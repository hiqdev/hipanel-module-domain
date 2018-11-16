<?php

namespace hipanel\modules\domain\helpers;

use hipanel\modules\domain\forms\CheckForm;
use Tuck\Sort\Sort;
use Tuck\Sort\SortChain;

/**
 * Class DomainSort provides sorting functions for domains.
 *
 * @author Andrey Klochok <andrey.klochok@gmail.com>
 */
class DomainSort
{
    /**
     * @return SortChain
     */
    public static function byGeneralRules(): SortChain
    {
        return Sort::chain()->asc(self::byDomainName());
    }

    private static function byDomainName(): \Closure
    {
        $order = [
            'com',
            'net',
            'name',
            'cc',
            'tv',
            'org',
            'info',
            'pro',
            'mobi',
            'biz',
            'me',
            'kiev.ua',
            'com.ua',
            'ru',
            'su',
            'xxx',
            'porn',
            'adult',
            'sex',
        ];

        return function (CheckForm $model) use ($order) {
            list(, $zone) = explode('.', $model->fqdn, 2);
            if (($key = array_search($zone, $order)) !== false) {
                return $key;
            }

            return INF;
        };
    }
}
