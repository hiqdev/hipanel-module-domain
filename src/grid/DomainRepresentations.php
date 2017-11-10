<?php

namespace hipanel\modules\domain\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class DomainRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'Common'),
                'columns' => [
                    'checkbox',
                    'domain',
                    'actions',
                    'client_like',
                    'seller',
                    'state',
                    'whois_protected',
                    'is_secured',
                    'created_date',
                    'expires',
                    'autorenewal',
                ],
            ],
        ]);
    }
}
