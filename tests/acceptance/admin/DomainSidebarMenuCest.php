<?php

namespace hipanel\modules\domain\tests\acceptance\admin;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Admin;
use yii\helpers\Url;

class DomainSidebarMenuCest
{
    public function ensureMenuIsOk(Admin $I)
    {
        $I->login();
        $menu = new SidebarMenu($I);

        $I->amOnPage(Url::to(['/']));
        $menu->ensureContains('Domains',[
            'Domains' => '/domain/domain/index',
            'Name Servers' => '/domain/host/index',
            'Contacts' => '/client/contact/index',
            'WHOIS lookup' => '/domain/whois/index',
            'DNS' => '/dns/zone/index',
        ]);

        $I->amOnPage(Url::to(['/']));
        $menu->ensureDoesNotContain('Domains', [
            'Buy domain',
            'Transfer domain',
        ]);
    }
}
