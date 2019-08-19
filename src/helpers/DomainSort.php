<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\helpers;

use hipanel\modules\domain\forms\CheckForm;
use hipanel\modules\domain\models\Domain;
use Tuck\Sort\Sort;
use Tuck\Sort\SortChain;
use Yii;

/**
 * Class DomainSort provides sorting functions for domains.
 *
 * @author Andrey Klochok <andrey.klochok@gmail.com>
 */
class DomainSort
{
    public static $defaultOrder = [
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
            if (($key = array_search($fqdn, $tokens, true)) !== false || ($key = array_search($domain, $tokens, true)) !== false) {
                return $key;
            }

            return INF;
        });
    }

    public static function byZone(): \Closure
    {
        $mapping = [
            CheckForm::class => 'fqdn',
            Domain::class => 'domain',
        ];
        $order = empty(Yii::$app->params['module.domain.order.list']) ? self::$defaultOrder : Yii::$app->params['module.domain.order.list'];

        return function ($model) use ($order, $mapping) {
            $fqdn = null;
            foreach ($mapping as $class => $attribute) {
                if ($model instanceof $class) {
                    $fqdn = $model->{$attribute};
                } else if (is_string($model)) {
                    $fqdn = $model;
                }
            }
            if ($fqdn) {
                list(, $zone) = explode('.', $fqdn, 2);
                if (($key = array_search($zone, $order, true)) !== false) {
                    return $key;
                }
            }

            return INF;
        };
    }
}
