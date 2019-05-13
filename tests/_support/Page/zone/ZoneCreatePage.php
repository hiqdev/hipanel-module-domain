<?php

namespace hipanel\modules\domain\tests\_support\Page\zone;

use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;

class ZoneCreatePage extends Authenticated
{
    public function seeZoneWasCreated(): string
    {
        $I = $this->tester;
        $I->closeNotification('Zone has been created');
        $I->seeInCurrentUrl('/domain/zone/view?id=');

        return $I->grabFromCurrentUrl('~id=(\d+)~');
    }

    public function seeZoneFormErrors(): void
    {
        $I = $this->tester;
        $I->waitForText('No. cannot be blank.');
        $I->waitForText('Name cannot be blank.');
        $I->waitForText('Registry cannot be blank.');
    }

    public function setupZoneForm(array $values): void
    {
        $I = $this->tester;
        $this->fillZoneForm($values);
        $I->pressButton('Save');
        $I->waitForPageUpdate();
    }

    private function fillZoneForm(array $values): void
    {
        $I = $this->tester;

        (new Dropdown($I, 'select[id*=state]'))
            ->setValue($values['state']);

        (new Select2($I, 'select[id$=registry]'))
            ->setValue($values['registry']);

        (new Input($I, 'input[id$=add_period]'))
            ->setValue($values['add_period']);

        (new Input($I, 'input[id$=add_limit]'))
            ->setValue($values['add_limit']);

        (new Input($I, 'input[id$=no]'))
            ->setValue($values['no']);

        (new Input($I, 'input[id$=name]'))
            ->setValue($values['name']);

        $I->executeJS(<<<JS
document.querySelector('input[id*=has_contacts]').click();
document.querySelector('input[id*=password_required]').click();
JS
        );
    }
}
