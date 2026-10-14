<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\widgets;

use yii\base\Widget;
use Yii;

class ServiceTerminationNotice extends Widget
{
    public const string COOKIE_NAME = 'service-termination-notice-dismissed';

    /**
     * YYYY-MM-DD the service termination takes effect; null leaves the date out of the text.
     */
    public ?string $startDate = null;

    public function run(): string
    {
        if ($this->isDismissedByCurrentUser()) {
            return '';
        }

        $this->registerClientScript();

        return $this->render('service-termination-notice', [
            'startDate' => $this->formatStartDate(),
        ]);
    }

    /**
     * Long date in the application language, e.g. "October 16, 2026" for English.
     */
    private function formatStartDate(): ?string
    {
        if ($this->startDate === null || $this->startDate === '') {
            return null;
        }

        return Yii::$app->formatter->asDate($this->startDate, 'long');
    }

    private function isDismissedByCurrentUser(): bool
    {
        // Set client-side via document.cookie (no Yii cookie signature), so it must be
        // read from the raw $_COOKIE superglobal, not Yii::$app->request->cookies —
        // Yii's CookieCollection silently drops unsigned cookies when cookie validation
        // is enabled (the default), which would otherwise make this check always fail.
        return isset($_COOKIE[$this->getCookieName()]);
    }

    // Scoped per user id so one account dismissing the notice in a shared browser
    // doesn't overwrite (and hide) another account's separate dismissal state.
    private function getCookieName(): string
    {
        return self::COOKIE_NAME . '-' . Yii::$app->user->id;
    }

    private function registerClientScript(): void
    {
        $cookieName = $this->getCookieName();

        $this->view->registerJs(<<<JS

(function () {
    var modal = $('#service-termination-notice-modal'),
        closeButton = $('.service-termination-notice-close'),
        dontShowAgain = $('#service-termination-notice-dont-show-again');

    closeButton.click(function (event) {
        event.preventDefault();
        if (dontShowAgain.is(':checked')) {
            var expires = new Date();
            expires.setFullYear(expires.getFullYear() + 5);
            document.cookie = '{$cookieName}=1; expires=' + expires.toUTCString() + '; path=/';
        }
        modal.modal('hide');
    });

    modal.modal('show');
})()

JS
        );
    }
}
