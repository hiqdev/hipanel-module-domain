<?php


namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\helpers\Url;
use hipanel\tests\_support\Step\Acceptance\Client;

class DomainCest
{
    public function ensureIndexPageWorks(Client $I)
    {
        $I->login();
        $I->needPage(Url::to('@domain/index'));
        $I->see('Domains', 'h1');
        $I->seeLink('Buy domain', Url::to('@domain-check'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkDomainSearchBox($I);
    }

    private function ensureICanSeeAdvancedSearchBox(Client $I)
    {
        $I->see('Advanced search', 'h3');
        $I->seeElement('input', ['placeholder' => 'Domain name']);
        $I->seeElement('textarea', ['placeholder' => 'Domain names (one per row)']);
        $I->seeElement('input', ['placeholder' => 'Notes']);
        $I->see('Status', 'span');
        $I->see('Registered range', 'label');
        $I->seeElement('input', ['name' => 'date-picker']);
        $I->see('Search', "//button[@type='submit']");
        $I->seeLink('Clear', Url::to('@domain/index'));
    }

    private function ensureICanSeeBulkDomainSearchBox(Client $I)
    {
        $sortColumns = [
            'domain' => 'Domain name',
            'created_date' => 'Registered',
            'expires' => 'Paid till',
        ];
        $I->seeSortColumns($sortColumns, '@domain/index');

        $I->see('Status', 'th');

        $columns = ['WHOIS', 'Protection', 'Autorenew'];
        foreach ($columns as $text) {
            $I->see($text, 'span');
        }

        $elements = [
            'WHOIS protection',
            'Protection from transfer',
            'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account',
        ];
        foreach ($elements as $element) {
            $I->seeElement('th', ['data-content' => $element]);
        }

        $I->see('No results found.', "//div[@class='empty']");
    }
}
