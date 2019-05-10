<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

class Calculation extends \hipanel\modules\finance\cart\Calculation
{
    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->object = 'domain';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['domain', 'zone'], 'safe'],
            [['id'], 'integer'],
        ]);
    }
}
