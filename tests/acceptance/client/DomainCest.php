<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Page\Widget\Input\Textarea;
use hipanel\tests\_support\Step\Acceptance\Client;

class DomainCest
{
    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Client $I)
    {
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Client $I)
    {
        $I->login();
        $I->needPage(Url::to('@domain/index'));
        $I->see('Domains', 'h1');
        $I->seeLink('Buy domain', Url::to('@domain-check'));
        $this->ensureICanSeeAdvancedSearchBox();
        $this->ensureICanSeeBulkDomainSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox()
    {
        $this->index->containsFilters([
            new Input('Domain name'),
            new Textarea('Domain names (one per row)'),
            new Input('Notes'),
            new Select2('Status'),
        ]);
    }

    private function ensureICanSeeBulkDomainSearchBox()
    {
        $this->index->containsBulkButtons([
            'Basic actions',
            'Set notes',
            'Set NS',
            'Change contacts',
        ]);
        $this->index->containsColumns([
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
