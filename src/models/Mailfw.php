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

use hipanel\helpers\StringHelper;
use Yii;
use yii\validators\EmailValidator;

class Mailfw extends \hipanel\base\Model
{
    use PaidFeatureForwardingTrait;

    public $status = 'new';

    public $typename = 'email';

    public function rules()
    {
        return [
            [['id', 'domain_id', 'dns_id', 'type_id'], 'integer'],
            [['name', 'value', 'type', 'type_label', 'status', 'typename'], 'string'],
            [['name', 'value'], 'required'],
            [['name'], 'match', 'pattern' => '@^[a-zA-Z0-9._*]+$@'],
            [
                ['value'],
                function ($attribute, $params) {
                    $validator = new EmailValidator();
                    $emails = StringHelper::mexplode($this->{$attribute}, '/[\s,]+/', true, true);
                    foreach ($emails as $email) {
                        if (!$validator->validate($email, $error)) {
                            $this->addError($attribute, $error);
                        }
                    }
                },
            ],
            [['name', 'value'], 'required', 'on' => ['validate-mailfw-form']],
            [['domain_id', 'status', 'name', 'value'], 'required',  'on' => ['validate-mailfw-form']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('hipanel.domain.premium', 'User name'),
            'value' => Yii::t('hipanel.domain.premium', 'Forwarding addresses'),
        ];
    }
}
