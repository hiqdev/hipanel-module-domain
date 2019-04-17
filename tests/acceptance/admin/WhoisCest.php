<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\tests\acceptance\admin;

use Codeception\Example;
use hipanel\tests\_support\Step\Acceptance\Admin;

/**
 * Class WhoisCest.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class WhoisCest
{
    protected function parent()
    {
        return new \hipanel\modules\domain\tests\acceptance\client\WhoisCest();
    }

    public function ensureIndexPageWorks(Admin $I)
    {
        $this->parent()->ensureIndexPageWorks($I);
    }

    public function _domainSearchProvider()
    {
        $items = $this->parent()->_domainSearchProvider();
        unset(
            $items['available-to-order-domain.com']['see']['add-to-cart-button'],
            $items['available-to-order-domain.com']['see']['availability-notice']
        );
        $items['available-to-order-domain.com']['dontSee'] = ['Register domain'];

        return $items;
    }

    /**
     * @dataprovider _domainSearchProvider
     */
    public function ensureSearchWorks(Admin $I, Example $example)
    {
        $this->parent()->ensureSearchWorks($I, $example);
    }
}
