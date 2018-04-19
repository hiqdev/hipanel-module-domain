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
use hipanel\grid\MainColumn;
use hipanel\grid\XEditableColumn;
use hipanel\modules\domain\controllers\DomainController;
use yii\helpers\Html;

class HostGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'host' => [
                'class' => MainColumn::class,
                'filterAttribute' => 'host_like',
            ],
            'bold_host' => [
                'format' => 'html',
                'attribute' => 'host',
                'value' => function ($model) {
                    return Html::tag('b', $model->host);
                },
            ],
            'domain' => [
                'format' => 'html',
                'filterAttribute' => 'domain_like',
                'value' => function ($model) {
                    $domain = explode('.', $model->host, 2)[1];

                    return $model->domain_id
                        ? Html::a($domain, DomainController::getActionUrl('view', $model->domain_id))
                        : Html::tag('b', $domain);
                },
            ],
            'ips' => [
                'class' => XEditableColumn::class,
                'pluginOptions' => [
                    'url' => 'update',
                ],
            ],
        ]);
    }
}
