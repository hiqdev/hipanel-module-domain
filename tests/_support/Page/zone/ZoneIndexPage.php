<?php

namespace hipanel\modules\domain\tests\_support\Page;

use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use WebDriverKeys;

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
            'Add grace period limit, %',
            'Auto-Renew grace period',
            'Redemption grace period',
        ]);
    }

    public function seeZoneWasCreated(): string
    {
        $I = $this->tester;
        $I->closeNotification('Zone has been created');
        $I->seeInCurrentUrl('/domain/zone/view?id=');

        return $I->grabFromCurrentUrl('~id=(\d+)~');
    }

    public function getCreatedZoneOnIndexPage($name)
    {
        $I = $this->tester;
        $sortinputSelector = "td input[name*=name]";
        (new Input($I, $sortinputSelector))
            ->setValue($name);
        $I->pressKey($sortinputSelector, WebDriverKeys::ENTER);
        $I->waitForPageUpdate();
    }
}