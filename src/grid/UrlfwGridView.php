<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\grid;

class UrlfwGridView extends AbstractPremiumGrid
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'name' => [
                'value' => function ($model) {
                    return ltrim(join('.', [$model->name, $this->domain]), '.');
                },
            ],
        ]);
    }
}
