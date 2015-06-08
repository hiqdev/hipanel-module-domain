<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\controllers;

use hipanel\modules\domain\models\Domain;

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
            'autorenew' => [
                'class' => 'hiqdev\bootstrap_switch\BootstrapSwitchAction',
                'scenario' => 'set-autorenewal',
                'modelclass' => Domain::className(),
            ],
            'SetWhoisProtect' => [
                'class' => 'hiqdev\bootstrap_switch\BootstrapSwitchAction',
                'scenario' => 'set-whois-protect',
                'modelclass' => Domain::className(),
            ],
            'SetLock' => [
                'class' => 'hiqdev\bootstrap_switch\BootstrapSwitchAction',
                'scenario' => 'set-lock',
                'modelclass' => Domain::className(),
            ],
        ];
    }
}