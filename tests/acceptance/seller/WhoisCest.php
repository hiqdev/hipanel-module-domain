<?php

namespace acceptance\seller\domain;

use Codeception\Example;
use Step\Acceptance\Seller;

class WhoisCest
{
    protected function parent()
    {
        return new \acceptance\client\domain\WhoisCest();
    }

    public function ensureIndexPageWorks(Seller $I)
    {
        $this->parent()->ensureIndexPageWorks($I);
    }

    public function _domainSearchProvider()
    {
        return $this->parent()->_domainSearchProvider();
    }

    /**
     * @dataprovider _domainSearchProvider
     */
    public function ensureSearchWorks(Seller $I, Example $example)
    {
        $this->parent()->ensureSearchWorks($I, $example);
    }
}
