<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\widgets\combo;

use hiqdev\combo\Combo;

class ZoneCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'domain/zone';

    /** {@inheritdoc} */
    public $name = 'text';

    /** {@inheritdoc} */
    public $url = '/domain/zone/get-zones';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_primaryFilter = 'search';
}
