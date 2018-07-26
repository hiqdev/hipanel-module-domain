<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\helpers\Url;
use hipanel\tests\_support\Step\Acceptance\Client;

class DomainCheckCest
{
    public function ensureIndexPageWorks(Client $I)
    {
        $I->login();
        $I->needPage(Url::to('@domain-check'));
        $I->see('Domain check', 'h1');
        $this->ensureICanSeeFilterSidebar($I);
        $this->ensureICanSeeDomainSearchBox($I);
    }

    private function ensureICanSeeFilterSidebar(Client $I)
    {
        $headers = [
            'Status' => [
                '.available' => 'Available',
                '.unavailable' => 'Unavailable',
            ],
            'Special' => [
                '.popular' => 'Popular Domains',
                '.promotion' => 'Promotion',
            ],
            'Categories' => [
                '.general' => 'General',
                '.internet' => 'Internet',
                '.sport' => 'Sport',
                '.society' => 'Society',
                '.geo' => 'GEO',
                '.nature' => 'Nature',
                '.audio_music' => 'Audio&Music',
                '.home_gifts' => 'Home&Gifts',
                '.adult' => 'Adult',
            ],
        ];
        foreach ($headers as $header => $list) {
            $I->see($header, 'h3');
            foreach ($list as $dataFilter => $text) {
                $I->see($text, "//a[@data-filter='$dataFilter']");
            }
        }
    }

    private function ensureICanSeeDomainSearchBox(Client $I)
    {
        $I->seeElement('input', [
            'type' => 'search',
            'placeholder' => '.com',
        ]);
        $I->seeElement('input', [
            'id' => 'bulkcheckform-fqdns',
            'placeholder' => 'Domain name',
        ]);
        $I->see('You can search by multiple domains for availability. Use a space, comma, semicolon or newline for word separation.', 'p');
        $I->see('Search', "//button[@type='submit']");
    }
}
