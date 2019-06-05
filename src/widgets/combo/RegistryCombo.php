<?php

namespace hipanel\modules\domain\widgets\combo;

use hiqdev\combo\Combo;
use Yii;

class RegistryCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'domain/zone';

    /** {@inheritdoc} */
    public $name = 'registry';

    /** {@inheritdoc} */
    public $url = '/domain/zone/index';

    /** {@inheritdoc} */
    public $_return = ['id', 'registry'];

    /** {@inheritdoc} */
    public $_primaryFilter = 'registry_ilike';
}
