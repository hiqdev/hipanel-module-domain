<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\controllers;

use hipanel\actions\RedirectAction;
use hipanel\actions\ValidateFormAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\client\actions\ContactCreateAction;
use hipanel\modules\client\actions\ContactUpdateAction;
use hipanel\modules\client\models\Contact;
use hipanel\modules\domain\cart\RegistrantModifier;
use hiqdev\yii2\cart\Module as CartModule;
use Yii;

class ContactController extends CrudController
{
    public static function modelClassName()
    {
        return Contact::class;
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'create' => 'contact.create',
                    'update' => 'contact.update',
                    '*' => 'contact.read',
                ],
            ],
        ]);
    }

    public function actions()
    {
        $cartModifierCallback = function ($event) {
            /** @var ContactUpdateAction $action */
            $action = $event->sender;
            $ids = $action->collection->getIds();
            (new RegistrantModifier(CartModule::getInstance()->getCart()))->setRegistrantId(reset($ids));
        };

        $postHtml = [
            'save' => true,
            'success' => [
                'class' => RedirectAction::class,
                'url' => ['@finance/cart/finish'],
            ],
            'error' => [
                'class' => RedirectAction::class,
                'url' => ['@domain-contact/request'],
            ],
        ];

        return array_merge(parent::actions(), [
            'create' => [
                'class' => ContactCreateAction::class,
                'scenario' => Yii::$app->request->get('requestPassport') ? 'create-require-passport' : 'create',
                'on afterPerform' => $cartModifierCallback,
                'POST html' => $postHtml,
            ],
            'update' => [
                'class' => ContactUpdateAction::class,
                'scenario' => Yii::$app->request->get('requestPassport') ? 'update-require-passport' : 'update',
                'on afterPerform' => $cartModifierCallback,
                'POST html' => $postHtml,
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
        ]);
    }

    public function actionRequest($requestPassport = null)
    {
        return $this->render('request', compact('requestPassport'));
    }
}
