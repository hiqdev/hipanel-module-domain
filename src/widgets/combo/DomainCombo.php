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

class DomainCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'domain/domain';

    /** {@inheritdoc} */
    public $name = 'domain';

    /** {@inheritdoc} */
    public $url = '/domain/domain/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'domain'];

    /** {@inheritdoc} */
    public $_primaryFilter = 'domain_like';
}
