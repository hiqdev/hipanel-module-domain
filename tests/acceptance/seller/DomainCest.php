<?php

namespace hipanel\modules\domain\tests\acceptance\seller;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Page\Widget\Input\Textarea;
use hipanel\tests\_support\Step\Acceptance\Seller;

class DomainCest
{
    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Seller $I)
    {
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Seller $I)
    {
        $I->login();
        $I->needPage(Url::to('@domain/index'));
        $I->see('Domains', 'h1');
        $I->seeLink('Register domain', Url::to('@domain-check'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkDomainSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox(Seller $I)
    {
        $this->index->containsFilters([
            Input::asAdvancedSearch($I, 'Domain name'),
            Textarea::asAdvancedSearch($I, 'Domain names (one per row)'),
            Input::asAdvancedSearch($I, 'Notes'),
            Select2::asAdvancedSearch($I, 'Client'),
            Select2::asAdvancedSearch($I, 'Reseller'),
            Select2::asAdvancedSearch($I, 'Status'),
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
            'Client',
            'Reseller',
            'Status',
            'WHOIS',
            'Protection',
            'Registered',
            'Paid till',
            'Auto renew',
        ]);
    }
}
