<?php

namespace hipanel\modules\domain\tests\acceptance\seller;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Seller;

class DomainSidebarMenuCest
{
    public function ensureMenuIsOk(Seller $I)
    {
        (new SidebarMenu($I))->ensureContains('Domains',[
            'Domains' => '@domain/index',
            'Name Servers' => '@host/index',
            'Contacts' => '@contact/index',
            'Register domain' => '@domain-check/check-domain',
            'Transfer domain' => '/domain/transfer/index',
            'WHOIS lookup' => '/domain/whois/index',
        ]);
    }
}
