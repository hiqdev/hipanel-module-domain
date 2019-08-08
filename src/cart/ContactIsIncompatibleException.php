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

use Yii;
use yii\helpers\Url;

class ContactIsIncompatibleException extends \hiqdev\yii2\cart\NotPurchasableException
{
    protected $requestPassport = false;

    public static function passportRequired()
    {
        $exception = new self();
        $exception->requestPassport = true;

        return $exception;
    }

    public static function generalDataRequired()
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
