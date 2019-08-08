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

    public static function passportRequired(): self
    {
        $exception = new self();
        $exception->requestPassport = true;

        return $exception;
    }

    public static function generalDataRequired(): self
    {
        return new self();
    }

    public function resolve(): bool
    {
        Yii::$app->response->redirect(Url::to(['@domain-contact/request', 'requestPassport' => $this->requestPassport]));
        Yii::$app->end();
        return false;
    }
}
