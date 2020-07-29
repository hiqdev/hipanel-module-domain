<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain;

/**
 * HiPanel Domain Plugin Module.
 */
class Module extends \hipanel\base\Module
{
    /**
     * @var bool Whether WHOIS Protect is paid
     */
    public $payableWhoisProtect = false;

    /**
     * @var bool Whether WHOIS Protect is paid
     */
    public $whoisProtectPaid = false;
}
