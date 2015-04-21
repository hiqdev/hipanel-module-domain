<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\widgets;

use hipanel\base\Re;

class State extends \hipanel\widgets\State
{
    /** @inheritdoc */
    public $model = [];
    public $states = [];
    public $defaultStates = [
        'info'      => ['ok'],
        'danger'    => ['blocked','expired'],
        'warning'   => [],
    ];
}
