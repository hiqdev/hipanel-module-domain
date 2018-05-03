<?php

namespace hipanel\modules\domain\tests\_support\Page;

use Codeception\Util\ActionSequence;
use hipanel\tests\_support\Page\Authenticated;
use yii\helpers\Url;

/**
 * Class Whois
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Whois extends Authenticated
{
    public function openIndexPage()
    {
        $I = $this->tester;

        $I->amOnPage(Url::to(['/domain/whois']));
        $I->performOnContent(ActionSequence::build()
            ->see('WHOIS lookup')
            ->see('WHOIS lookup result')
            ->see('You can check WHOIS information here. Just enter domain name in the form input.')
        );
    }

    public function searchFor($domain)
    {
        $I = $this->tester;

        $I->fillField('#whois-domain', $domain);
        $I->click('#whois-lookup button[type="submit"]');
        $I->wait(1);
        $I->waitForElementNotVisible('#whois .progress', 15);
    }
}
