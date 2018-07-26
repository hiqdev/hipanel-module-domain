<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\GridView;
use hipanel\tests\_support\Step\Acceptance\Client;

class HostCest
{
    public function ensureIndexPageWorks(Client $I)
    {
        $I->login();
        $I->needPage(Url::to('@host'));
        $I->see('Name Servers', 'h1');
        $I->seeLink('Create name server', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkHostSearchBox($I);

    }

    private function ensureICanSeeAdvancedSearchBox(Client $I)
    {
        $I->see('Advanced search', 'h3');
        $I->seeElement('input', [
            'placeholder' => 'Name server',
            'id' => 'hostsearch-host_like',
        ]);
        $I->seeElement('input', [
            'placeholder' => 'Domain name',
            'id' => 'hostsearch-domain_like',
        ]);
        $I->see('Search', "//button[@type='submit']");
        $I->seeLink('Clear', Url::to('@host/index'));
    }

    private function ensureICanSeeBulkHostSearchBox(Client $I)
    {
        $sortColumns = [
            'host' => 'Host',
            'ips' => 'IPs',
            'domain' => 'Domain name',
        ];
        $gridView = new GridView($I);
        $gridView->containsColumns($sortColumns, '@host/index');

        $I->see('Set IPs', "//button[@type='button']");
        $I->see('Delete', "//button[@type='submit']");
        $I->see('No results found.', "//div[@class='empty']");
    }
}
