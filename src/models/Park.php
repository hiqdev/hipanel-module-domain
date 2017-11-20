<?php

namespace hipanel\modules\domain\models;

class Park extends \hipanel\base\Model
{
    public function rules()
    {
        return [
            [['id', 'domain_id', 'dns_id', 'type_id'], 'integer'],
            [['title', 'siteheader', 'sitetext'], 'string'],
        ];
    }
}
