<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Step\Acceptance\Client;

class HostCest
{
    public function ensureIndexPageWorks(Client $I)
    {
        $I->login();
        $I->needPage(Url::to('@host'));
        $I->see('Name Servers', 'h1');
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkHostSearchBox($I);
    }

    private function ensureICanSeeAdvancedSearchBox(Client $I)
    {
        $I->see('Advanced search', 'h3');
        $I->seeLink('Create name server', Url::to('create'));

        $index = new IndexPage($I);
        $index->containsFilters('form-advancedsearch-host-search', [
            ['input' => [
                'id' => 'hostsearch-host_like',
                'placeholder' => 'Name server',
            ]],
            ['input' => [
                'id' => 'hostsearch-domain_like',
                'placeholder' => 'Domain name',
            ]],
        ]);
    }

    private function ensureICanSeeBulkHostSearchBox(Client $I)
    {
        $index = new IndexPage($I);
        $index->containsBulkButtons([
            ["//button[@type='button']" => 'Set IPs'],
            ["//button[@type='submit']" => 'Delete'],
        ]);
        $index->containsColumns('bulk-host-search', [
            'Host',
            'IPs',
            'Domain name',
        ]);
    }
}
