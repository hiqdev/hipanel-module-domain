<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
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
    public $foaField = 'foa_sent_to';

    public function init()
    {
        parent::init();

        $this->addFoaStatus();
    }

    protected function addFoaStatus()
    {
        if (!$this->model->hasAttribute($this->foaField)) {
            return;
        }

        if ($this->model->state !== $this->model::STATE_PREINCOMING || $this->model->{$this->foaField} === null) {
            return;
        }

        $this->label .= ' ' . $this->model->{$this->foaField};
    }
}
