<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (http://hiqdev.com/)
 */

/**
 * @link    http://hiqdev.com/hipanel-module-domain
 *
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */
namespace hipanel\modules\domain\widgets;

class State extends \hipanel\widgets\Type
{
    /** {@inheritdoc} */
    public $model = [];
    public $values = [];
    public $defaultValues = [
        'none'   => ['ok'],
        'danger'    => ['blocked','expired'],
        'warning'   => [],
    ];
    public $field = 'state';
}
