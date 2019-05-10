<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\tests\acceptance\client;

use hipanel\helpers\Url;
use hipanel\tests\_support\Step\Acceptance\Client;

class TransferCest
{
    public function ensureIndexPageWorks(Client $I)
    {
        $I->login();
        $I->needPage(Url::to('/domain/transfer'));
        $I->see('Domain transfer', 'h1');
        $this->ensureICanSeeSingleTransferBox($I);
        $this->ensureICanSeeBulkTransferBox($I);
        $I->see('Transfer', "//button[@type='submit']");
    }

    private function ensureICanSeeSingleTransferBox(Client $I)
    {
        $I->click(['link' => 'Domain transfer']);
        $I->see('Remove WHOIS protection from the current registrar.');
        $I->see('Domain name', 'label');
        $I->see('Transfer (EPP) password', 'label');
        $I->see('An email was sent to your email address specified in Whois. To start the transfer, click on the link in the email.');
    }

    private function ensureICanSeeBulkTransferBox(Client $I)
    {
        $I->click(['link' => 'Bulk domain transfer']);
        $I->see('Domains', 'label');
        $I->see('For separation of the domain and code use a space, a comma or a semicolon. Example:', 'p');
        $I->see('yourdomain.com uGt6shlad', 'p');
        $I->see('each pair (domain + code) should be written with a new line', 'p');
    }
}
