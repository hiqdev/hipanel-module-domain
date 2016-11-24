<?php

namespace hipanel\modules\domain\actions;

use hipanel\actions\RenderJsonAction;
use hipanel\actions\SmartPerformAction;
use Yii;

class DomainOptionSwitcherAction extends SmartPerformAction
{
    /** {@inheritdoc} */
    protected function getDefaultRules()
    {
        return array_merge(parent::getDefaultRules(), [
            'POST ajax' => [
                'save' => true,
                'flash' => true,
                'success' => [
                    'class' => RenderJsonAction::class,
                    'return' => function ($action) {
                        $message = Yii::$app->session->removeFlash('success');
                        return [
                            'success' => true,
                            'text' => Yii::t('hipanel:domain', reset($message)['text']),
                        ];
                    },
                ],
                'error' => [
                    'class' => RenderJsonAction::class,
                    'return' => function ($action) {
                        $message = Yii::$app->session->removeFlash('error');
                        return [
                            'success' => false,
                            'text' => reset($message)['text']
                        ];
                    },
                ],
            ],
        ]);
    }
}
