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

use hipanel\grid\BoxedGridView;
use hipanel\grid\MainColumn;
use hipanel\modules\domain\models\Secdns;
use hipanel\modules\domain\controllers\DomainController;
use hiqdev\combo\StaticCombo;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class SecdnsGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'domain' => [
                'format' => 'html',
                'attribute' => 'domain',
                'filterAttribute' => 'domain_like',
                'value' => function ($model) {

                    return Html::a($model->domain, DomainController::getActionUrl('view', $model->domain_id));
                },
            ],
            'key_tag' => [
                'format' => 'raw',
                'attribute' => 'key_tag',
                'value' => function($model) {
                    return Html::tag('span', $model->key_tag);
                }
            ],
            'digest_alg' => [
                'attribute' => 'digest_alg',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('span', $model->getDigestAlgorithm());
                },
            ],
            'digest_type' => [
                'attribute' => 'digest_type',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('span', $model->getDigestType());
                },
            ],
            'digest' => [
                'attribute' => 'digest',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('span', $model->digest);
                },
            ],
            'key_alg' => [
                'attribute' => 'key_alg',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('span', $model->getKeyAlgorithm());
                },
            ],
            'pub_key' => [
                'attribute' => 'pub_key',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('span', $model->pub_key);
                },
            ],
        ]);
    }
}
