<?php

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
