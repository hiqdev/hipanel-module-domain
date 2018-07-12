<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\modules\domain\tests\_support\Page\Whois;
use hipanel\tests\_support\Step\Acceptance\Client;

/**
 * Class WhoisCest
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
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
            'ripe.net' => [
                'domain' => 'ripe.net',
                'see' => [
                    'Feb 25, 1992',
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
