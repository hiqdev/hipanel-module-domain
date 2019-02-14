<?php

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\modules\domain\tests\_support\Domain;
use hipanel\modules\domain\tests\_support\OpenDomainViewPage;
use hipanel\modules\domain\tests\_support\Page\DomainIndexPage;
use hipanel\modules\domain\tests\_support\Page\DomainViewPage;
use hipanel\tests\_support\Step\Acceptance\Client;
use hipanel\helpers\Url;


class DomainChangeNSsCest
{
    /** @var DomainViewPage */
    private $viewPage;

    /** @var Domain */
    private $domain;

    private $successNotification = 'Name servers changed';

    public function _before(Client $I)
    {
        $I->login();
    }

    public function ensureIOpenTestDomainPage(Client $I)
    {
        $indexPage = new DomainIndexPage($I);
        $this->viewPage = new DomainViewPage($I);
        $this->domain = new Domain();

        $I->needPage(Url::to('@domain/index'));
        $domainId = $indexPage->getDomainId($this->domain->getName());
        $this->domain->setDomainId($domainId);
        $I->needPage(Url::to('@domain/view?id=' . $domainId));
        $I->see($this->domain->getName(), 'h1');
    }

    public function ensureICanAddNS(Client $I)
    {
        $nsAmount = $this->viewPage->countNSs();
        $this->addNs();
        $I->pressButton('Save');
        $I->closeNotification($this->successNotification);
        $this->viewPage->checkAmountOfNSs($nsAmount + 1);
    }

    public function ensureICanDeleteNS(Client $I)
    {
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
