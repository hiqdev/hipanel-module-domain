<?php

namespace hipanel\modules\domain\models;

class Premium extends \hipanel\base\Model
{
    public function rules()
    {
        return [
            [['id', 'domain_id', 'days_left'], 'integer'],
            [['is_active', 'is_autorenewal'], 'boolean'],
            [['expires'], 'datetime'],
        ];
    }
}