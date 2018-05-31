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
            'Contacts' => '/client/contact/index',
            'WHOIS lookup' => '/domain/whois/index',
            'DNS' => '/dns/zone/index',
        ]);
    }
}
