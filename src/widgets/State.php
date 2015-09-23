<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\widgets;

class State extends \hipanel\widgets\Type
{
    /** @inheritdoc */
    public $model = [];
    public $values = [];
    public $defaultValues = [
        'none'   => ['ok'],
        'danger'    => ['blocked','expired'],
        'warning'   => [],
    ];
    public $field = 'state';
}
