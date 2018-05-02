<?php

namespace acceptance\admin\domain;

use Codeception\Example;
use Step\Acceptance\Admin;

class WhoisCest
{
    protected function parent()
    {
        return new \acceptance\client\domain\WhoisCest();
    }

    public function ensureIndexPageWorks(Admin $I)
    {
        $this->parent()->ensureIndexPageWorks($I);
    }

    public function _domainSearchProvider()
    {
        $items = $this->parent()->_domainSearchProvider();
        unset($items['available-to-order-domain.com']['see']['add-to-cart-button']);
        $items['available-to-order-domain.com']['dontSee'] = ['Buy domain'];

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
