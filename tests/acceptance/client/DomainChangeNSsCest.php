<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\modules\domain\tests\_support\Page\DomainIndexPage;
use hipanel\modules\domain\tests\_support\Page\DomainViewPage;
use hipanel\tests\_support\Step\Acceptance\Client;
use hipanel\helpers\Url;


class DomainChangeNSsCest
{
    /** @var string  */
    private $testDomain = 'vylys.com';

    /** @var string */
    private $domainId;

    /** @var DomainViewPage */
    private $viewPage;

    private $successNotification = 'Name servers changed';

    public function ensureIHaveTestingDomain(Client $I)
    {
        $indexPage = new DomainIndexPage($I);

        $I->needPage(Url::to('@domain/index'));
        $this->domainId = $indexPage->getDomainId($this->testDomain);
        $I->needPage(Url::to('@domain/view?id=' . $this->domainId));
        $I->see($this->testDomain, 'h1');
    }

    public function ensureICanAddNS(Client $I)
    {
        $this->getViewPage($I);

        $nsAmount = $this->viewPage->countNSs();
        $this->addNs();
        $I->pressButton('Save');
        $I->closeNotification($this->successNotification);
        $this->viewPage->checkAmountOfNSs($nsAmount + 1);
    }

    public function ensureICanDeleteNS(Client $I)
    {
        $this->getViewPage($I);

        if (($n = $this->viewPage->countNSs()) <= 1) {
            $this->addNs();
        };

        $this->viewPage->deleteLastNS();
        $I->pressButton('Save');
        $I->closeNotification($this->successNotification);
    }

    private function addNs(): void
    {
        $nss = $this->viewPage->getNSs();
        $nsName = $this->getNewNSName($nss);
        $this->viewPage->addNS($nsName);
    }

    /**
     * @param Client $I
     */
    private function getViewPage(Client $I): void
    {
        $this->viewPage = new DomainViewPage($I);
    }

    /**
     * @param array $nss
     * @return string
     */
    private function getNewNSName(array $nss): string
    {
        for ($i = 1; $i <= count($nss) + 1; $i++) {
            $nsName = "ns{$i}.topdns.me";
            if (!in_array($nsName, $nss)) {
                break;
            }
        }

        return $nsName ?? "ns1.topdns.me";
    }
}
