<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

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
            'Register domain',
            'Transfer domain',
        ]);
    }
}
