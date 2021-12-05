<?php
declare(strict_types=1);

namespace hipanel\modules\domain\tests\acceptance\seller;

use Codeception\Example;
use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Step\Acceptance\Seller;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\MultipleSelect2;

class DomainRegisterCest
{
    /**
     * @dataProvider provideDomainData
     */
    public function ensureMainPageWorksCorrcetly(Seller $I, Example $example): void
    {
        $I->login();
        $domain = iterator_to_array($example->getIterator());

        $I->needPage(Url::to('@domain/index'));
        $I->see('Domains', 'h1');
        $I->seeLink('Register domain', Url::to('@domain-check'));

        $I->clickLink('Register domain');
        $I->waitForPageUpdate();
        $this->ensureRegisterPageWorksCorrcetly($I, $domain);
        $this->ensureSearchWorksCorrectly($I, $domain);
    }

    private function ensureRegisterPageWorksCorrcetly(Seller $I, array $domain): void
    {
        $I->see('Domain check', 'h1');
        (new Input($I, '#bulkcheckform-fqdns'))->setValue($domain['name']);
        (new MultipleSelect2($I, '#bulkcheckform-zones'))->setValue($domain['zone']);
        $I->pressButton('Search');
    }

    private function ensureSearchWorksCorrectly(Seller $I, array $domain): void
    {
        $selector = "//div[contains(@class, 'tab-content domain-list')]";

        $I->waitForElement($selector, 10);
        $I->clickLink('Exact search');

        $I->see($domain['name']. $domain['zone'], $selector);
    }

    protected function provideDomainData(): array
    {
        return [
            [
                'name' => 'hiqdev',
                'zone'        => '.com',
            ],
        ];
    }
}
