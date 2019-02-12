<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\modules\domain\tests\_support\Page\DomainIndexPage;
use hipanel\tests\_support\Step\Acceptance\Client;
use hipanel\helpers\Url;


class DomainChangeNSsCest
{
    /** @var \hipanel\tests\_support\Page\IndexPage */
    private $indexPage;

    /** @var string  */
    private $testDomain = 'vylys.com';

    /** @var string */
    private $domainId;

    public function _before(Client $I)
    {
        $this->indexPage = new DomainIndexPage($I);

        $I->login();
        $I->needPage(Url::to('@domain/index'));
        $this->domainId = $this->indexPage->getDomainId($this->testDomain);
        $I->needPage(Url::to('@domain/view?id=' . $this->domainId));
    }

}
