<?php

namespace hipanel\modules\domain\tests\_support\Page;

use hipanel\tests\_support\AcceptanceTester;
use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Input;

class DomainViewPage extends Authenticated
{
    /** @var string  */
    private $nsPaneCssId = '#ns-records';

    /** @var string  */
    private $settingsPaneCssId = '#domain-settings';

    /** @var @var string */
    private $nsRowSelector;

    public function __construct(AcceptanceTester $I)
    {
        parent::__construct($I);

        $this->nsRowSelector = $this->nsPaneCssId . ' .item';
    }

    /**
     * @param string $nsName
     */
    public function addNS(string $nsName): void
    {
        $addButtonSelector = $this->nsRowSelector . ':last-child .add-item';
        $this->tester->click($addButtonSelector);

        $inputSelector = $this->nsPaneCssId . ' div.item:last-child input[id*="name"]';
        (new Input($this->tester, $inputSelector))
            ->setValue($nsName);
    }

    /**
     * @return int
     * @throws \Codeception\Exception\ModuleException
     */
    public function countNSs(): int
    {
        return $this->tester->countElements($this->nsPaneCssId . ' .item');
    }

    /**
     * @return array
     */
    public function getNSs(): array
    {
        $selector = $this->nsRowSelector . ' input[id^="ns"]';
        return $this->tester->grabMultiple($selector, 'value');
    }

    /**
     * @param int $nssAmount
     */
    public function checkAmountOfNSs(int $nssAmount): void
    {
        $this->tester->seeNumberOfElements($this->nsPaneCssId . ' .item', $nssAmount);
    }

    public function deleteLastNS(): void
    {
        $this->tester->click($this->nsRowSelector . ':last-child .remove-item');
    }

    /**
     * @param string $settingName
     */
    public function switchSetting(string $settingName): void
    {
        $this->tester->click($this->settingsPaneCssId . " div[class *= '${settingName}']");
    }

    /**
     * @param string $note
     */
    public function setNote(string $note): void
    {
        $this->tester->click($this->settingsPaneCssId . " a[data-name='note']");
        (new Input($this->tester, $this->settingsPaneCssId . ' div.popover input'))
            ->setValue($note);
        $this->tester->click($this->settingsPaneCssId . " div.popover button[class*='submit']");
    }

    public function getAuthorizationCode()
    {
        return $this->tester->grabTextFrom('#authcode-static');
    }
}
