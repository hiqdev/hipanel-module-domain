<?php

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
