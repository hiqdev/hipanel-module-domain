<?php

namespace hipanel\modules\domain\tests\acceptance\seller;

use hipanel\helpers\Url;
use hipanel\modules\domain\tests\_support\Page\ZonePage;
use hipanel\tests\_support\Step\Acceptance\Seller;

class ZonesCest
{
    /**
     * @var ZonePage $zonePage
     */
    private $zonePage;
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
        $this->zonePage = new ZonePage($I);
        $this->testZoneValues = $this->getZoneTestData();
    }

    public function ensureIndexPageWorks(Seller $I): void
    {
        $I->needPage(Url::to('@zone/index'));
        $I->see('Zone', 'h1');
        $I->seeLink('Create zone', Url::to('create'));
        $this->zonePage->ensureICanSeeAdvancedSearchBox();
        $this->zonePage->ensureICanSeeBulkSearchBox();
    }

    public function ensureICanCreateZone(Seller $I): void
    {
        $I->needPage(Url::to('@zone/create'));
        $I->click('Save');
        $I->waitForPageUpdate();
        $this->zonePage->seeZoneFormErrors();
        $this->zonePage->setupZoneForm($this->testZoneValues);
        $this->zoneId = $this->zonePage->seeZoneWasCreated();
    }

    public function ensureICanSeeViewPage(Seller $I): void
    {
        $I->needPage(Url::to('@zone/view?id=' . $this->zoneId));
        $I->see($this->testZoneValues['name'], 'h1');
    }

    public function ensureICanUpdateZone(Seller $I): void
    {
        $page = $this->zonePage;
        $I->needPage(Url::to('@zone/update?id='.$this->zoneId));
        $this->updateValues();
        $page->setupZoneForm($this->testZoneValues);
        $I->see($this->testZoneValues['name'], 'h1');
    }

    public function ensureICanDisableZone(Seller $I): void
    {
        $I->needPage(Url::to('@zone/index'));
        $this->zonePage->getCreatedZoneOnIndexPage($this->testZoneValues['name']);
        $I->executeJS(<<<JS
document.querySelector("input[type=checkbox][value='$this->zoneId']").click();
JS
        );
        $I->click('Disable');
        $I->closeNotification('Zone has been disabled');
    }

    public function ensureICanEnableZone(Seller $I): void
    {
        $I->needPage(Url::to('@zone/index'));
        $this->zonePage->getCreatedZoneOnIndexPage($this->testZoneValues['name']);
        $I->executeJS(<<<JS
document.querySelector("input[type=checkbox][value='$this->zoneId']").click();
JS
        );
        $I->click('Enable');
        $I->closeNotification('Zone has been enabled');
    }

    protected function getZoneTestData(): array
    {
        return [
            'registry'          => 'untagged',
            'state'             => 'Ok',
            'name'              => '.test',
            'no'                => time(),
            'add_period'        => '100',
            'add_limit'         => '50',
        ];
    }

    protected function updateValues(): void
    {
        $this->testZoneValues['state'] = 'Not working';
        $this->testZoneValues['no'] = time();
        $this->testZoneValues['name'] .= '1';
    }
}