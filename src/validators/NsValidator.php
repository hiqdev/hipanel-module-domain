<?php

/*
 * Client Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-client
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (https://hiqdev.com/)
 */

namespace hipanel\modules\domain\validators;

use hipanel\validators\DomainValidator;
use yii\validators\IpValidator;

/**
 * Class NsValidator is used to validate NS and IP.
 */
class NsValidator extends \yii\validators\Validator
{
    /**
     * Splits $value by `/`, checks each part with the proper validator
     *
     * {@inheritdoc}
     */
    public function validateValue($value) {
        list($domain, $ip) = explode('/', $value, 2);
        if (($error = (new DomainValidator())->validateValue($domain)) !== null) {
            return $error;
        }
        if ($ip !== null && ($error = (new IpValidator())->validateValue($ip)) !== null) {
            return $error;
        }
        return null;
    }
}
