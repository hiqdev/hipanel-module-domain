<?php

namespace hipanel\modules\domain\helpers;

use hipanel\modules\domain\forms\CheckForm;
use hipanel\modules\domain\models\Domain;
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
        return Sort::chain()->asc(self::byZone());
    }

    public static function bySearchQueryTokens(array $tokens = []): SortChain
    {
        $tokens = array_map('mb_strtolower', $tokens);

        return Sort::chain()->asc(self::byZone())->asc(function (CheckForm $model) use ($tokens) {
            $fqdn = mb_strtolower($model->fqdn);
            [$domain,] = explode('.', $fqdn, 2);
            if (($key = array_search($fqdn, $tokens)) !== false || ($key = array_search($domain, $tokens)) !== false) {
                return $key;
            }

            return INF;
        });
    }

    public static function byZone(): \Closure
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
        $mapping = [
            CheckForm::class => 'fqdn',
            Domain::class => 'domain',
        ];
        return function ($model) use ($order, $mapping) {
            $fqdn = null;
            foreach ($mapping as $class => $attribute) {
                if ($model instanceof $class) {
                    $fqdn = $model->{$attribute};
                }
            }
            if ($fqdn) {
                list(, $zone) = explode('.', $fqdn, 2);
                if (($key = array_search($zone, $order)) !== false) {
                    return $key;
                }
            }

            return INF;
        };
    }
}
