<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use Yii;
use yii\base\Event;

class HostController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class'     => IndexAction::class,
                'filterStorageMap' => [
                    'domain_like' => 'domain.domain.domain_like',
                    'ips' => 'hosting.ip.ip_in',
                    'client_id' => 'client.client.id',
                    'seller_id' => 'client.client.seller_id',
                ],
            ],
            'view' => [
                'class'     => ViewAction::class,
            ],
            'validate-form' => [
                'class'     => ValidateFormAction::class,
            ],
            'create' => [
                'class'     => SmartCreateAction::class,
                'success'   => Yii::t('hipanel:domain', 'Name server created'),
            ],
            'update' => [
                'class'     => SmartUpdateAction::class,
                'success'   => Yii::t('hipanel:domain', 'Name server updated'),
                'POST html' => [
                    'save'    => true,
                    'success' => [
                        'class' => RedirectAction::class,
                    ],
                ],
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $bulkIps = Yii::$app->request->post('bulk_ips');
                    if (!empty($bulkIps)) {
                        foreach ($action->collection->models as $model) {
                            $model->ips = $bulkIps;
                        }
                    }
                },
            ],
            'bulk-set-ips' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'update',
                'view' => '_bulkSetIps',
            ],
            'delete' => [
                'class'     => SmartPerformAction::class,
                'success'   => Yii::t('hipanel:domain', 'Name server deleted'),
            ],
        ];
    }
}
