<?php

namespace hipanel\modules\domain\tests\acceptance\seller;

use hipanel\helpers\Url;
use hipanel\modules\domain\tests\_support\Page\zone\ZoneIndexPage;
use hipanel\modules\domain\tests\_support\Page\zone\ZoneCreatePage;
use hipanel\modules\domain\tests\_support\Page\zone\ZoneUpdatePage;
use hipanel\tests\_support\Step\Acceptance\Seller;

class ZonesCest
{
    /**
     * @var ZoneIndexPage $zoneIndexPage
     */
    private $zoneIndexPage;
    /**
     * @var ZoneCreatePage $zoneCreatePage
     */
    private $zoneCreatePage;
    /**
     * @var ZoneUpdatePage $zoneUpdatePage
     */
    private $zoneUpdatePage;
    /**
     * @var string $zoneId
     */
    private $zoneId;
    /**
     * @var array $testZoneValues
     */
    private $testZoneValues;

    public function _before(Seller $I): void
    {
        $this->zoneIndexPage = new ZoneIndexPage($I);
        $this->zoneCreatePage  = new ZoneCreatePage($I);
        $this->zoneUpdatePage  = new ZoneUpdatePage($I);
        $this->testZoneValues = $this->getZoneTestData();
    }

    public function ensureIndexPageWorks(Seller $I): void
    {
        $I->needPage(Url::to('@zone/index'));
        $I->see('Zone', 'h1');
        $I->seeLink('Create zone', Url::to('create'));
        $this->zoneIndexPage->ensureICanSeeAdvancedSearchBox();
        $this->zoneIndexPage->ensureICanSeeBulkSearchBox();
    }

    public function ensureICanCreateZone(Seller $I): void
    {
        $I->needPage(Url::to('@zone/create'));
        $I->click('Save');
        $I->waitForPageUpdate();
        $this->zoneCreatePage->seeZoneFormErrors();
        $this->zoneCreatePage->setupZoneForm($this->testZoneValues);
        $this->zoneId = $this->zoneCreatePage->seeZoneWasCreated();
    }

    public function ensureICanSeeViewPage(Seller $I): void
    {
        $I->needPage(Url::to('@zone/view?id=' . $this->zoneId));
        $I->see($this->testZoneValues['name'], 'h1');
    }

    public function ensureICanUpdateZone(Seller $I): void
    {
        $page = $this->zoneUpdatePage;
        $I->needPage(Url::to('@zone/update?id='.$this->zoneId));
        $this->updateValues();
        $page->setupZoneForm($this->testZoneValues);
        $I->see($this->testZoneValues['name'], 'h1');
    }

    public function ensureICanDisableZone(Seller $I): void
    {
        $I->needPage(Url::to('@zone/index'));
        $this->zoneIndexPage->getCreatedZoneOnIndexPage($this->testZoneValues['name']);
        $this->zoneIndexPage->checkBoxClick($this->zoneId);
        $I->click('Disable');
        $I->closeNotification('Zone has been disabled');
    }

    public function ensureICanEnableZone(Seller $I): void
    {
        $I->needPage(Url::to('@zone/index'));
        $this->zoneIndexPage->getCreatedZoneOnIndexPage($this->testZoneValues['name']);
        $this->zoneIndexPage->checkBoxClick((string)$this->zoneId);
        $I->click('Enable');
        $I->closeNotification('Zone has been enabled');
    }

    protected function getZoneTestData(): array
    {
        return [
            'registry'                  => 'untagged',
            'state'                     => 'Ok',
            'name'                      => '.testtt',
            'no'                        => time(),
            'add_period'                => '100',
            'add_limit'                 => '50',
            'has_contacts'              => '1',
            'password_required'         => '0',
            'autorenew_grace_period'    => '',
            'redemption_grace_period'   => '',
        ];
    }

    protected function updateValues(): void
    {
        $this->testZoneValues['state'] = 'Not working';
        $this->testZoneValues['no'] = time();
        $this->testZoneValues['name'] .= '1';
    }
}
