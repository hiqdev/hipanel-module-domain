<?php

namespace hipanel\modules\domain\tests\_support\Page\zone;

use Exception;
use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\CheckBox;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;

class ZoneCreatePage extends Authenticated
{
    /**
     * @return string
     */
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

    /**
     * @param array $values
     * @throws \Codeception\Exception\ModuleException
     */
    public function setupZoneForm(array $values): void
    {
        $I = $this->tester;
        $this->fillZoneForm($values);
        $I->pressButton('Save');
        $I->waitForPageUpdate();
    }

    /**
     * @param array $values
     * @throws Exception
     */
    private function fillZoneForm(array $values): void
    {
        $I = $this->tester;

        (new Dropdown($I, 'select[id*=state]'))
            ->setValue($values['state']);

        (new Select2($I, 'select[id$=registry]'))
            ->setValue($values['registry']);

        (new Input($I, 'input[id$=add_grace_period]'))
            ->setValue($values['add_period']);

        (new Input($I, 'input[id$=add_grace_limit]'))
            ->setValue($values['add_limit']);

        (new Input($I, 'input[id$=no]'))
            ->setValue($values['no']);

        (new Input($I, 'input[id$=name]'))
            ->setValue($values['name']);

        $this->checkBoxSetValue('has_contacts', $values['has_contacts']);
        $this->checkBoxSetValue('password_required', $values['password_required']);
    }

    /**
     * @param string $field
     * @param string $value
     */
    private function checkBoxSetValue(string $field, string $value): void
    {
        $I = $this->tester;
        $checkBoxSelector = "input[type=checkbox][id*=$field]";
        (new CheckBox($I, $checkBoxSelector))
            ->setValue($value);
    }
}
