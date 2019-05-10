<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\cart;

use Yii;

class DomainTransferPurchase extends AbstractDomainPurchase
{
    public $registrant;

    /**
     * @var string the EPP password for the domain transfer
     */
    public $password;

    public function init()
    {
        parent::init();
        $this->registrant = $this->position->registrant;
    }

    /** {@inheritdoc} */
    public static function operation()
    {
        return 'Transfer';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['password'], 'required'],
            [['registrant'], 'integer'],
        ]);
    }

    public function renderNotes()
    {
        return $this->isRuZone()
            ? Yii::t('hipanel:domain', 'Transfer confirmation email would be send to domain owner after comparing and checking contact data from our partner ARDIS-RU')
            : '';
    }

    public function getPurchasabilityRules()
    {
        return [
            DomainContactsCompatibilityValidator::class,
        ];
    }

    public function execute()
    {
        $res = parent::execute();
        if ($res) {
            $view = Yii::$app->getView();
            if (!$this->isRuZone()) {
                $view->params['remarks']['transferAttention'] = $view->render('@hipanel/modules/domain/views/domain/_transferAttention');
            } else {
                $view->params['remarks']['transferAttention'] = '';
            }
        }

        return $res;
    }
}
