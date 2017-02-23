<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\widgets;

use yii\helpers\Html;

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
    public $addField = 'foa_sent_to';

    public function init()
    {
        parent::init();

        if (($this->model->state !== $this->model::STATE_PREINCOMING) || !$this->model->hasAttribute($this->addField))
        {
            return ;
        }

        if ($this->model->{$this->addField} === null)
        {
            return ;
        }

        $this->label .= " " . $this->model->{$this->addField};
    }
}
