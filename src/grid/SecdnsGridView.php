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
                'format' => 'raw',
                'attribute' => 'domain',
                'filterAttribute' => 'domain_like',
                'value' => function ($model): string {
                    return Html::a(Html::encode($model->domain), DomainController::getActionUrl('view', $model->domain_id));
                },
            ],
            'key_tag' => [
                'format' => 'raw',
                'attribute' => 'key_tag',
                'value' => function($model): string {
                    return Html::tag('span', isset($model->key_tag) ? Html::encode($model->key_tag) : '');
                }
            ],
            'digest_alg' => [
                'attribute' => 'digest_alg',
                'format' => 'raw',
                'value' => function($model): string {
                    $alg = $model->getDigestAlgorithm();
                    if (in_array($model->digest_alg, Secdns::deprecatedAlgorithms(), true)) {
                        return Html::tag('span', Html::encode($alg), ['class' => 'text-danger'])
                            . ' '
                            . Html::tag('span', Yii::t('hipanel:domain', 'Deprecated'), ['class' => 'label label-danger']);
                    }
                    return Html::tag('span', Html::encode($alg));
                },
            ],
            'digest_type' => [
                'attribute' => 'digest_type',
                'format' => 'raw',
                'value' => function($model): string {
                    $type = $model->getDigestType();
                    if (in_array($model->digest_type, Secdns::deprecatedDigestTypes(), true)) {
                        return Html::tag('span', Html::encode($type), ['class' => 'text-danger'])
                            . ' '
                            . Html::tag('span', Yii::t('hipanel:domain', 'Deprecated'), ['class' => 'label label-danger']);
                    }
                    return Html::tag('span', Html::encode($type));
                },
            ],
            'digest' => [
                'attribute' => 'digest',
                'format' => 'raw',
                'value' => function($model): string {
                    return Html::tag('span', isset($model->digest) ? Html::encode($model->digest) : '');
                },
            ],
            'key_alg' => [
                'attribute' => 'key_alg',
                'format' => 'raw',
                'value' => function($model): string {
                    $alg = $model->getKeyAlgorithm();
                    if (in_array($model->key_alg, Secdns::deprecatedAlgorithms(), true)) {
                        return Html::tag('span', Html::encode($alg), ['class' => 'text-danger'])
                            . ' '
                            . Html::tag('span', Yii::t('hipanel:domain', 'Deprecated'), ['class' => 'label label-danger']);
                    }
                    return Html::tag('span', Html::encode($alg));
                },
            ],
            'pub_key' => [
                'attribute' => 'pub_key',
                'format' => 'raw',
                'value' => function($model): string {
                    return Html::tag('span', isset($model->pub_key) ? Html::encode($model->pub_key) : '');
                },
            ],
        ]);
    }
}
