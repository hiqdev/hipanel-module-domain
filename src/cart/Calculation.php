<?php

namespace hipanel\modules\domain\cart;

class Calculation extends \hipanel\modules\finance\models\Calculation
{
    /** @inheritdoc */
    public function init()
    {
        parent::init();

        $this->object = 'domain';
    }

    /** @inheritdoc */
    public function rules() {
        return array_merge(parent::rules(), [
            [['zone'], 'safe']
        ]);
    }
}