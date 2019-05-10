<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\models;

use hipanel\modules\dns\validators\DomainPartValidator;
use Yii;

class Urlfw extends \hipanel\base\Model
{
    use PaidFeatureForwardingTrait;

    public $status = 'not changed';

    public function rules()
    {
        return [
            [['id', 'domain_id', 'dns_id', 'type_id'], 'integer'],
            [['name', 'value', 'type', 'type_label', 'currentTab', 'status'], 'string'],
            [['type', 'value'], 'required'],
            [['name'], DomainPartValidator::class],
            [['value'], 'url'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('hipanel.domain.premium', 'Subdomain name'),
            'type_label' => Yii::t('hipanel.domain.premium', 'Record type'),
            'type' => Yii::t('hipanel.domain.premium', 'Record type'),
            'value' => Yii::t('hipanel.domain.premium', 'Forwarding address'),
        ];
    }
}
