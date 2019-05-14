<?php

namespace hipanel\modules\domain\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\models\Ref;
use Yii;

class Zone extends Model
{
    use ModelTrait;

    const STATE_OK = 'ok';
    const STATE_NOT_WORKING = 'notworking';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer', 'on' => ['create', 'update']],
            [['name', 'registry', 'no', 'state', 'add_grace_period', 'autorenew_grace_period', 'redemption_grace_period'], 'string', 'on' => ['create', 'update']],
            [['has_contacts', 'password_required'], 'boolean', 'on' => ['create', 'update']],
            [['name', 'registry', 'no', 'state'], 'required', 'on' => ['create', 'update']],
            [['id'], 'required', 'on' => ['update', 'delete']],
            [['add_grace_limit'], 'integer', 'min' => 0, 'max' => 100],
            [['id', 'state', 'registry'], 'safe', 'on' => ['enable', 'disable']]
        ];
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'has_contacts' => Yii::t('hipanel:domain', 'Has contacts'),
            'password_required' => Yii::t('hipanel:domain', 'Password required'),
            'registry' => Yii::t('hipanel:domain', 'Registry'),
            'name' => Yii::t('hipanel:domain', 'Name'),
            'state' => Yii::t('hipanel:domain', 'State'),
            'no' => Yii::t('hipanel:domain', 'No.'),
            'add_grace_period' => Yii::t('hipanel:domain', 'Add grace period'),
            'add_grace_limit' => Yii::t('hipanel:domain', 'Add grace limit'),
        ]);
    }

    public function scenarioActions()
    {
        return [
            'disable' => 'update',
            'enable' => 'update',
        ];
    }

    public function getTypeList()
    {
        return Ref::getList('state,zone');
    }
}
