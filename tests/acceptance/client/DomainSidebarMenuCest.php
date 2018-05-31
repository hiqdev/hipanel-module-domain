<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Client;
use yii\helpers\Url;

class DomainSidebarMenuCest
{
    public function ensureMenuIsOk(Client $I)
    {
        $I->login();
        $menu = new SidebarMenu($I);

        $I->amOnPage(Url::to(['/']));
        $menu->ensureContains('Domains',[
            'Domains' => '/domain/domain/index',
            'Name Servers' => '/domain/host/index',
            'Contacts' => '/client/contact/index',
            'Buy domain' => '/domain/check/check-domain',
            'Transfer domain' => '/domain/transfer/index',
            'WHOIS lookup' => '/domain/whois/index',
            'DNS' => '/dns/zone/index',
        ]);
    }
}
