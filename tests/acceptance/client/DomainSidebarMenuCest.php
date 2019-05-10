<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

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
