<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\tests\acceptance\seller;

use Codeception\Example;
use hipanel\tests\_support\Step\Acceptance\Seller;

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
