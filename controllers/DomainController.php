<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\controllers;

use hipanel\modules\domain\models\Domain;
use Yii;

class DomainController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'setnote' => [
                'class' => 'hiqdev\xeditable\XEditableAction',
                'scenario' => 'set-note',
                'modelclass' => Domain::className(),
            ],
            'set-autorenewal' => [
                'class'   => 'hipanel\actions\SwitchAction',
                'success' => Yii::t('app', 'Record was changed'),
                'error'   => Yii::t('app', 'Error occurred!'),
                'POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class'  => 'hipanel\actions\ProxyAction',
                        'action' => 'index'
                    ]
                ],
                'POST'    => [
                    'save'    => true,
                    'success' => [
                        'class'  => 'hipanel\actions\RenderJsonAction',
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        }
                    ]
                ],
            ],
            'set-whois-protect' => [
                'class'   => 'hipanel\actions\SwitchAction',
                'success' => Yii::t('app', 'Record was changed'),
                'error'   => Yii::t('app', 'Error occurred!'),
                'POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class'  => 'hipanel\actions\ProxyAction',
                        'action' => 'index'
                    ]
                ],
                'POST'    => [
                    'save'    => true,
                    'success' => [
                        'class'  => 'hipanel\actions\RenderJsonAction',
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        }
                    ]
                ],
            ],
            'set-lock' => [
                'class' => 'hipanel\actions\SwitchAction',
                'success' => Yii::t('app', 'Record was changed'),
                'error'   => Yii::t('app', 'Error occurred!'),
                'POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class'  => 'hipanel\actions\ProxyAction',
                        'action' => 'index'
                    ]
                ],
                'POST'    => [
                    'save'    => true,
                    'success' => [
                        'class'  => 'hipanel\actions\RenderJsonAction',
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        }
                    ]
                ],
            ],
        ];
    }

    public function actionView($id)
    {
//        $model = Domain::findOne(['id' => $id, 'with_dns' => 1]);
        $model = Domain::findOne($id);
        $domainContactInfo = Domain::perform('GetContactsInfo', ['id' => $id]);

        return $this->render('view', [
            'model' => $model,
            'domainContactInfo' => $domainContactInfo,
        ]);
    }
}