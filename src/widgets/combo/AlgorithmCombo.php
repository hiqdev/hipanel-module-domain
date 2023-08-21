<?php
/**
 * Combo widgets for algorithm
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
  * @copyright Copyright (c) 2023, HiQDev (http://hiqdev.com/)
  */

namespace hipanel\modules\domain\widgets\combo;

use hiqdev\combo\StaticCombo;

class AlgorithmCombo extends StaticCombo
{
    /** {@inheritdoc} */
    public function init()
    {
        parent::init();
        foreach (($this->data ?? []) as $no => $name) {
            $this->data[$no] = "{$no} - {$name}";
        }
    }
}

