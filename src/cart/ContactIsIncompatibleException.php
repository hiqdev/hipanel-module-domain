<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use hiqdev\yii2\cart\NotPurchasableException;
use Yii;
use yii\helpers\Url;

final class ContactIsIncompatibleException extends NotPurchasableException
{
    private $requestPassport = false;

    private $requestRegistrant = false;

    private $registrant = null;

    public static function passportRequired($registrant = null): self
    {
        $exception = new self();
        $exception->requestPassport = true;

        if ($registrant) {
            $exception->registrant = $registrant;
        }

        return $exception;
    }

    public static function generalDataRequired($registrant = null): self
    {
        if (empty($registrant)) {
            return new self();
        }

        $exception =  new self();
        $exception->registrant = $registrant;

        return $exception;
    }

    public static function registrantRequired(): self
    {
        $exception = new self();
        $exception->requestRegistrant = true;

        return $exception;
    }

    public function resolve(): bool
    {
        Yii::$app->response->redirect(Url::to(array_filter([
            '@domain-contact/request',
            'requestPassport' => $this->requestPassport,
            'requestRegistrant' => $this->requestRegistrant,
            'registrant' => $this->registrant,
        ])));
        Yii::$app->end();
        return false;
    }
}
