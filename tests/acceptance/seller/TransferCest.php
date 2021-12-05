<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\tests\acceptance\seller;

use hipanel\helpers\Url;
use hipanel\tests\_support\Step\Acceptance\Seller;
use hipanel\tests\_support\Page\Widget\Input\Input;

class TransferCest
{
    public function ensureIndexPageWorks(Seller $I): void
    {
        $I->login();
        $I->needPage(Url::to('/domain/transfer'));
        $I->see('Domain transfer', 'h1');
        $this->ensureICanSeeSingleTransferBox($I);
        $this->ensureICanSeeBulkTransferBox($I);
        $I->see('Transfer', "//button[@type='submit']");
        $this->enusreICantTransferWithInvalidData($I);
    }

    private function ensureICanSeeSingleTransferBox(Seller $I): void
    {
        $I->click(['link' => 'Domain transfer']);
        $I->see('Remove WHOIS protection from the current registrar.');
        $I->see('Domain name', 'label');
        $I->see('Transfer (EPP) password', 'label');
        $I->see('An email was sent to your email address specified in Whois. To start the transfer, click on the link in the email.');
    }

    private function ensureICanSeeBulkTransferBox(Seller $I): void
    {
        $I->click(['link' => 'Bulk domain transfer']);
        $I->see('Domains', 'label');
        $I->see('For separation of the domain and code use a space, a comma or a semicolon. Example:', 'p');
        $I->see('yourdomain.com uGt6shlad', 'p');
        $I->see('each pair (domain + code) should be written with a new line', 'p');
    }

    private function enusreICantTransferWithInvalidData($I): void
    {
        $I->click(['link' => 'Domain transfer']);
        $I->pressButton('Transfer');
        $this->ensureContainsError($I, [
            'Domain name',
            'Transfer (EPP) password',
        ]);
    }

    private function enusreICantBulkTransferWithInvalidData($I): void
    {
        $I->click(['link' => 'Domain transfer']);
        $I->pressButton('Transfer');
        $this->ensureContainsError($I, ['Domains',]);
    }

    private function ensureContainsError(Seller $I, array $fieldsList): void
    {
        foreach ($fieldsList as $field) {
            $I->waitForText("$field cannot be blank.");
        }
    }
}
