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

use Closure;
use hipanel\modules\finance\models\DomainResource;
use Tuck\Sort\Sort;
use Tuck\Sort\SortChain;

/**
 * Class DomainSort provides sorting functions for domains.
 *
 * @author Andrey Klochok <andrey.klochok@gmail.com>
 */
class DomainSort
{
    public static function byZoneNo(): SortChain
    {
        return Sort::chain()->asc(self::byZoneNoOrder());
    }

    private static function byZoneNoOrder(): Closure
    {
        return static function (DomainResource $zone) {
            return $zone->no;
        };
    }
}
