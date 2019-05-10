<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\grid;

use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use hipanel\modules\domain\models\Zone;
use hipanel\modules\domain\widgets\combo\RegistryCombo;
use hipanel\widgets\IconStateLabel;
use Yii;
use yii\bootstrap\Html;
use yii\grid\DataColumn;

class ZoneGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'name' => [
                'attribute' => 'name',
                'format' => 'raw',
                'label' => Yii::t('hipanel:domain', 'Name'),
                'filterAttribute' => 'name_ilike',
                'value' => function (Zone $model) {
                    return Html::a($model->name, ['@zone/view', 'id' => $model->id]);
                },
            ],
            'registry' => [
                'label' => Yii::t('hipanel:domain', 'Registry'),
                'attribute' => 'registry',
                'filterOptions' => ['class' => 'narrow-filter'],
                'format' => 'raw',
                'filterAttribute' => 'registry_ilike',
                'filter' => function (DataColumn $column, Zone $model, string $attribute) {
                    return RegistryCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
                'value' => function (Zone $model) {
                    return Html::hiddenInput("registry[$model->id]", $model->registry) . $model->registry;
                },
            ],
            'state' => [
                'label' => Yii::t('hipanel:domain', 'State'),
                'attribute' => 'state',
                'filterOptions' => ['class' => 'narrow-filter'],
                'class' => RefColumn::class,
                'format' => 'raw',
                'gtype' => 'state,zone',
                'value' => function (Zone $model) {
                    return Yii::t('hipanel:domain', $model->state);
                },
            ],
            'no' => [
                'attribute' => 'no',
                'filter' => false,
                'format' => 'raw',
                'label' => Yii::t('hipanel:domain', 'No.'),
                'filterAttribute' => 'no_ilike',
            ],
            'autorenew' => [
                'attribute' => 'autorenew',
                'filter' => false,
                'format' => 'raw',
                'label' => Yii::t('hipanel:domain', 'Auto-Renew grace period'),
            ],
            'redemption' => [
                'attribute' => 'redemption',
                'filter' => false,
                'format' => 'raw',
                'label' => Yii::t('hipanel:domain', 'Redemption grace period'),
            ],
            'add_period' => [
                'attribute' => 'add_period',
                'filter' => false,
                'format' => 'raw',
                'label' => Yii::t('hipanel:domain', 'Add grace period'),
            ],
            'add_limit' => [
                'attribute' => 'add_limit',
                'filter' => false,
                'format' => 'raw',
                'label' => Yii::t('hipanel:domain', 'Add grace period limit') . ', %',
            ],
            'has_contacts' => [
                'encodeLabel' => false,
                'filter' => false,
                'enableSorting' => false,
                'attribute' => 'has_contacts',
                'format' => 'raw',
                'label' => Yii::t('hipanel:domain', 'Has contacts'),
                'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle;'],
                'value' => function (Zone $model) {
                    return IconStateLabel::widget([
                        'model' => $model,
                        'attribute' => 'has_contacts',
                        'icons' => ['fa-check'],
                        'messages' => [
                            Yii::t('hipanel:domain', 'Has contacts'),
                            Yii::t('hipanel:domain', 'Has no contacts'),
                        ],
                    ]);
                },
            ],
            'password_required' => [
                'encodeLabel' => false,
                'filter' => false,
                'enableSorting' => false,
                'attribute' => 'password_required',
                'format' => 'raw',
                'label' => Yii::t('hipanel:domain', 'Password required'),
                'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle;'],
                'value' => function (Zone $model) {
                    return IconStateLabel::widget([
                        'model' => $model,
                        'attribute' => 'password_required',
                        'icons' => ['fa-key'],
                        'messages' => [
                            Yii::t('hipanel:domain', 'Password is required'),
                            Yii::t('hipanel:domain', 'Password is not required'),
                        ],
                    ]);
                },
            ],
        ]);
    }
}
