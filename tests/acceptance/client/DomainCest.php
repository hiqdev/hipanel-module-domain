<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Step\Acceptance\Client;

class DomainCest
{
    public function ensureIndexPageWorks(Client $I)
    {
        $I->login();
        $I->needPage(Url::to('@domain/index'));
        $I->see('Domains', 'h1');
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkDomainSearchBox($I);
    }

    private function ensureICanSeeAdvancedSearchBox(Client $I)
    {
        $I->see('Advanced search', 'h3');

        $index = new IndexPage($I);
        $index->containsFilters('form-advancedsearch-domain-search', [
            ['input' => ['placeholder' => 'Domain name']],
            ['textarea' => ['placeholder' => 'Domain names (one per row)']],
            ['input' => ['placeholder' => 'Notes']],
            ['input' => ['name' => 'date-picker']],
        ]);

        $I->see('Status', 'span');
        $I->see('Registered range', 'label');

        $index->containsButtons([
            ['a' => 'Buy domain'],
            ["//button[@type='submit']" => 'Search'],
            ['a' => 'Clear'],
            ["//button[@type='button']" => 'Basic actions'],
            ["//button[@type='button']" => 'Set notes'],
            ["//button[@type='button']" => 'Set NS'],
            ["//button[@type='button']" => 'Change contacts'],
        ]);
    }

    private function ensureICanSeeBulkDomainSearchBox(Client $I)
    {
        $index = new IndexPage($I);
        $index->containsColumns('bulk-domain-search', [
            'Domain name',
            'Status',
            'WHOIS',
            'Protection',
            'Registered',
            'Paid till',
            'Autorenew',
        ]);
    }
}
