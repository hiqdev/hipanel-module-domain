<?php

namespace hipanel\modules\domain\tests\acceptance\admin;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Admin;

class DomainSidebarMenuCest
{
    public function ensureMenuIsOk(Admin $I)
    {
        $menu = new SidebarMenu($I);

        $menu->ensureContains('Domains',[
            'Domains' => '@domain/index',
            'Name Servers' => '@host/index',
            'Contacts' => '@contact/index',
            'WHOIS lookup' => '/domain/whois/index',
        ]);

        $menu->ensureDoesNotContain('Domains', [
            'Buy domain',
            'Transfer domain',
        ]);
    }
}
