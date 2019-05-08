<?php

namespace hipanel\modules\domain\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\models\Ref;
use Yii;

class Zone extends Model
{
    use ModelTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'add_period', 'registry_id'], 'integer', 'on' => ['create', 'update']],
            [['name', 'registry', 'no', 'state', 'add_period', 'autorenew', 'redemption'], 'string', 'on' => ['create', 'update']],
            [['has_contacts', 'password_required'], 'boolean', 'on' => ['create', 'update']],
            [['name', 'registry', 'no', 'state'], 'required', 'on' => ['create', 'update']],
            [['id'], 'required', 'on' => ['update', 'delete']],
            [['add_limit'], 'integer', 'min' => 0, 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'has_contacts' => Yii::t('hipanel:domain', 'Has contacts'),
            'password_required' => Yii::t('hipanel:domain', 'Password required'),
            'provider' => Yii::t('hipanel:domain', 'Provider'),
            'name' => Yii::t('hipanel:domain', 'Name'),
            'state' => Yii::t('hipanel:domain', 'State'),
            'no' => Yii::t('hipanel:domain', 'No.'),
            'add_period' => Yii::t('hipanel:domain', 'Add period'),
            'add_limit' => Yii::t('hipanel:domain', 'Add period limit'),
        ]);
    }

    public function getTypeList()
    {
        return Ref::getList('state,zone');
    }
}
