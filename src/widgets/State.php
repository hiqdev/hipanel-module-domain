<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\widgets;

class State extends \hipanel\widgets\Type
{
    /** {@inheritdoc} */
    public $model = [];
    public $values = [];
    public $defaultValues = [
        'none'   => ['ok'],
        'danger'    => ['blocked', 'expired'],
        'warning'   => [],
    ];
    public $field = 'state';
}
