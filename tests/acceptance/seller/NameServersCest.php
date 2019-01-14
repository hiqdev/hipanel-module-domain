<?php

namespace hipanel\modules\domain\tests\acceptance\seller;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Seller;

class NameServersCest
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
        $I->needPage(Url::to('@host'));
        $I->see('Name Servers', 'h1');
        $I->seeLink('Create name server', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkHostSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox(Seller $I)
    {
        $this->index->containsFilters([
            Input::asAdvancedSearch($I, 'Name server'),
            Input::asAdvancedSearch($I, 'Domain name'),
            Select2::asAdvancedSearch($I, 'Client'),
            Select2::asAdvancedSearch($I, 'Reseller'),
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
            'Client',
            'Reseller',
        ]);
    }
}
