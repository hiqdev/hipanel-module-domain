<?php

namespace hipanel\modules\domain\tests\acceptance\client\domain;

use hipanel\helpers\Url;
use hipanel\tests\_support\Step\Acceptance\Client;
use hipanel\modules\domain\tests\_support\Domain;
use hipanel\modules\domain\tests\_support\Page\DomainIndexPage;
use hipanel\modules\domain\tests\_support\Page\DomainViewPage;

class DomainSettingsCest
{
    /** @var DomainViewPage */
    private $viewPage;

    /** @var Domain */
    private $domain;

    public function _before(Client $I)
    {
        $I->login();
    }

    public function ensureIOpenTestDomainPage(Client $I)
    {
        $indexPage      = new DomainIndexPage($I);
        $this->viewPage = new DomainViewPage($I);
        $this->domain   = new Domain();

        $I->needPage(Url::to('@domain/index'));
        $domainId = $indexPage->getDomainId($this->domain->getName());
        $this->domain->setDomainId($domainId);
        $I->needPage(Url::to('@domain/view?id=' . $domainId));
        $I->see($this->domain->getName(), 'h1');
    }

    public function ensureICanChangeNote(Client $I)
    {
        $I->needPage(Url::to('@domain/view?id=' . $this->domain->getDomainId()));

        $note = time();
        $this->viewPage->setNote($note);
        $I->waitForPageUpdate();
        $I->see($note);
    }

    public function ensureICanChangeWhoisSettings(Client $I)
    {
        $I->needPage(Url::to('@domain/view?id=' . $this->domain->getDomainId()));

        $this->viewPage->switchSetting('whois');
        $I->closeNotification('WHOIS protect has been changed');
    }

    public function ensureICanChangeProtectionSettings(Client $I)
    {
        $I->needPage(Url::to('@domain/view?id=' . $this->domain->getDomainId()));

        $this->viewPage->switchSetting('secure');
        $I->closeNotification('Lock has been changed');
    }

    public function ensureICanChangeAutorenewalSettings(Client $I)
    {
        $I->needPage(Url::to('@domain/view?id=' . $this->domain->getDomainId()));

        $this->viewPage->switchSetting('autorenewal');
        $I->closeNotification('Autorenewal has been changed');
    }

    public function ensureICanChangeAuthorizationCode(Client $I)
    {
        $I->needPage(Url::to('@domain/view?id=' . $this->domain->getDomainId()));

        $I->pressButton('Show');
        $I->waitForPageUpdate();
        $oldAuthCode = $this->viewPage->getAuthorizationCode();

        $I->pressButton('Generate new');
        $I->waitForPageUpdate();
        $I->closeNotification('The password has been changed');

        $I->pressButton('Show');
        $I->waitForPageUpdate();
        $I->dontSee($oldAuthCode);
    }
}
