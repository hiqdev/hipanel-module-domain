<?php

namespace acceptance\client\domain;

use Page\domain\Whois;
use Step\Acceptance\Client;

class WhoisCest
{
    public function ensureIndexPageWorks(Client $I)
    {
        (new Whois($I))->openIndexPage();
    }

    /**
     * @dataprovider _domainSearchProvider
     */
    public function ensureSearchWorks(Client $I, \Codeception\Example $example)
    {
        $whois = new Whois($I);
        $whois->openIndexPage();
        $whois->searchFor($example['domain']);
        if (isset($example['see'])) {
            foreach ($example['see'] as $item) {
                $I->see($item);
            }
        }

        if (isset($example['dontSee'])) {
            foreach ($example['dontSee'] as $item) {
                $I->dontSee($item);
            }
        }
    }

    public function _domainSearchProvider()
    {
        return [
            'mail.ru' => [
                'domain' => 'mail.ru',
                'see' => [
                    'Sep 27, 1997',
                    'Russian Federation',
                ]
            ],
            'ripe.net' => [
                'domain' => 'ripe.net',
                'see' => [
                    'Feb 25, 1992',
                    'Netherlands',
                ]
            ],
            'unexisting.domain' => [
                'domain' => 'unexisting.domain',
                'see' => [
                    'Domain name is not registered yet, or the domain zone is not supported.',
                ]
            ],
            'available-to-order-domain.com' => [
                'domain' => 'available-to-order-domain.com',
                'see' => [
                    'This domain is available for registration',
                    'add-to-cart-button' => 'Buy domain'
                ]
            ],
        ];
    }
}
