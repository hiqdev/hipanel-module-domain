<?php

namespace hipanel\modules\domain\cart;

class PremiumCalculation extends \hipanel\modules\finance\cart\Calculation
{
    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->object = 'feature';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['domain', 'exprires'], 'safe'],
            [['id'], 'integer'],
        ]);
    }
}
