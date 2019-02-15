<?php

namespace hipanel\modules\domain\tests\acceptance\client\domain;

use hipanel\modules\domain\tests\_support\Domain;
use hipanel\modules\domain\tests\_support\Page\DomainIndexPage;
use hipanel\modules\domain\tests\_support\Page\DomainViewPage;
use hipanel\tests\_support\Step\Acceptance\Client;
use hipanel\helpers\Url;

class DomainDnsCest
{
    /** @var DomainViewPage */
    private $viewPage;

    /** @var Domain */
    private $domain;

    /** @var string  */
    private $dnsRecord = 'test-record';

    /** @var string  */
    private $ip = '192.168.42.21';

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

        $I->click('DNS records');
        $I->waitForPageUpdate();
    }

    public function ensureICanCreateDnsRecord(Client $I)
    {
        $this->viewPage->fillDnsRecordInput('name', DomainViewPage::CREATE, $this->dnsRecord);
        $this->viewPage->fillDnsRecordInput('value', DomainViewPage::CREATE, $this->ip);

        $I->pressButton('Create');
        $I->waitForPageUpdate();
        $I->closeNotification('DNS record created successfully');
    }

    public function ensureICanUpdateDnsRecord(Client $I)
    {
        $this->viewPage->pressUpdateButtonFor(
            $this->dnsRecord . '.' . $this->domain->getName()
        );
        $I->waitForPageUpdate();

        $this->dnsRecord = 'updated-record';
        $this->ip = '255.255.255.255';

        $this->viewPage->fillDnsRecordInput('name', DomainViewPage::UPDATE, $this->dnsRecord);
        $this->viewPage->fillDnsRecordInput('value', DomainViewPage::UPDATE, $this->ip);

        $I->pressButton('Save', '//tr');
        $I->waitForPageUpdate();
        $I->closeNotification('DNS record updated successfully');

        $I->see($this->dnsRecord . '.' . $this->domain->getName());
        $I->see($this->ip);
    }

    public function ensureICanDeleteDnsRecord(Client $I)
    {
        $this->viewPage->pressDeleteButtonFor(
            $this->dnsRecord . '.' . $this->domain->getName()
        );
        $I->waitForPageUpdate();

        $this->viewPage->confirmRecordDeletion();
        $I->waitForPageUpdate();
        $I->closeNotification('DNS record deleted successfully');

        $I->dontSee($this->dnsRecord);
    }
}
