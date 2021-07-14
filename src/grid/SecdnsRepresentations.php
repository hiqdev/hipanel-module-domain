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

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class SecdnsRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'Common'),
                'columns' => [
                    'checkbox',
                    'domain',
                    'client',
                    'seller',
                    'key_tag',
                    'digest_alg',
                    'digest_type',
                    'digest',
                    'key_alg',
                    'pub_key',
                ],
            ],
        ]);
    }
}
