<?php

namespace hipanel\modules\domain\tests\_support\Page\zone;

use Facebook\WebDriver\WebDriverKeys;
use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\CheckBox;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;

class ZoneIndexPage extends Authenticated
{
    public function ensureICanSeeAdvancedSearchBox(): void
    {
        $I = $this->tester;
        (new IndexPage($I))->containsFilters([
            Select2::asAdvancedSearch($I, 'Registry'),
            Dropdown::asAdvancedSearch($I, 'State')->withItems([
                'Ok',
                'Not working',
            ]),
            Input::asAdvancedSearch($I, 'Name'),
        ]);
    }

    public function ensureICanSeeBulkSearchBox(): void
    {
        $I = $this->tester;
        (new IndexPage($I))->containsColumns([
            'Registry',
            'Name',
            'Has contacts',
            'Password required',
            'State',
            'No.',
            'Add grace period',
            'Add grace limit, %',
            'Auto-Renew grace period',
            'Redemption grace period',
        ]);
    }

    public function checkBoxClick(string $zoneId): void
    {
        $I = $this->tester;
        $checkBoxSelector = "input[type=checkbox][value='$zoneId']";
        (new CheckBox($I, $checkBoxSelector))
            ->setValue('1');
    }

    /**
     * @param $name
     * @throws \Codeception\Exception\ModuleException
     */
    public function getCreatedZoneOnIndexPage($name)
    {
        $I = $this->tester;
        $sortInputSelector = "td input[name*=name_ilike]";
        (new Input($I, $sortInputSelector))
            ->setValue($name);
        $I->pressKey($sortInputSelector, WebDriverKeys::ENTER);
        $I->waitForPageUpdate();
    }
}
