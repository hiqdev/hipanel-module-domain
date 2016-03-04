<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use Yii;

class DomainTransferPurchase extends AbstractDomainPurchase
{
    /** {@inheritdoc} */
    public static function operation()
    {
        return 'Transfer';
    }

    /**
     * @var string the EPP password for the domain transfer
     */
    public $password;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['password'], 'required'],
        ]);
    }

    public function renderNotes()
    {
        return Yii::t('hipanel/domain', 'Transfer confirmation email was sent to:') . ' <b>' . $this->_result['email'] . '</b>';
    }

    public function execute()
    {
        $res = parent::execute();
        if ($res) {
            $view = Yii::$app->getView();
            $view->params['remarks']['transfer_attention'] = $view->render('@hipanel/modules/domain/views/domain/_transferAttention');
        }

        return $res;
    }
}
