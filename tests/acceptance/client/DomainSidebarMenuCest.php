<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Client;

class DomainSidebarMenuCest
{
    public function ensureMenuIsOk(Client $I)
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
