<?php

namespace hipanel\modules\domain\tests\_support\Page;

use hipanel\tests\_support\AcceptanceTester;
use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Input;

class DomainViewPage extends Authenticated
{
    /** @var string  */
    private $nsPaneCssId = '#ns-records';

    /** @var @var string */
    private $nsRowSelector;

    public function __construct(AcceptanceTester $I)
    {
        parent::__construct($I);

        $this->nsRowSelector = $this->nsPaneCssId . ' .item';
    }

    public function addNS($nsName)
    {
        $addButtonSelector = $this->nsRowSelector . ':last-child .add-item';
        $this->tester->click($addButtonSelector);

        $inputSelector = $this->nsPaneCssId . ' div.item:last-child input[id*="name"]';
        (new Input($this->tester, $inputSelector))
            ->setValue($nsName);
    }

    public function countNSs()
    {
        return $this->tester->countElements($this->nsPaneCssId . ' .item');
    }

    public function getNSs()
    {
        $selector = $this->nsRowSelector . ' input[id^="ns"]';
        return $this->tester->grabMultiple($selector, 'value');
    }

    public function checkAmountOfNSs($nssAmount)
    {
        $this->tester->seeNumberOfElements($this->nsPaneCssId . ' .item', $nssAmount);
    }

    public function deleteLastNS()
    {
        $this->tester->click($this->nsRowSelector . ':last-child .remove-item');
    }
}
