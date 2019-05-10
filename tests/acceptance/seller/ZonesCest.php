<?php


namespace hipanel\modules\domain\tests\acceptance\seller;


use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Step\Acceptance\Seller;

class ZonesCest
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
        $I->needPage(Url::to('@zone/index'));
        $I->see('Zone', 'h1');
        $I->seeLink('Create zone', Url::to('create'));
    }
}