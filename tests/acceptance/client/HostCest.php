<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Client;

class HostCest
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
        $I->needPage(Url::to('@host'));
        $I->see('Name Servers', 'h1');
        $I->seeLink('Create name server', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox();
        $this->ensureICanSeeBulkHostSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox()
    {
        $this->index->containsFilters([
            new Input('Name server'),
            new Input('Domain name'),
        ]);
    }

    private function ensureICanSeeBulkHostSearchBox()
    {
        $this->index->containsBulkButtons([
            'Set IPs',
            'Delete',
        ]);
        $this->index->containsColumns([
            'Host',
            'IPs',
            'Domain name',
        ]);
    }
}
