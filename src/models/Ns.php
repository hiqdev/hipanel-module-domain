<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\models;

use hipanel\base\Model;
use hipanel\helpers\StringHelper;
use hipanel\modules\dns\validators\FqdnValueValidator;
use Yii;

class Ns extends Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['name', 'domain_name'], 'filter', 'filter' => 'trim'],
            [['name'],  FqdnValueValidator::className()],
            [['ip'], 'filter', 'filter' => function ($value) {
                return StringHelper::explode($value, ',', true, true);
            }, 'skipOnArray' => true],
            [['ip'], 'each', 'rule' => ['ip']],
            [['ip'],  function ($attribute, $params) {
                if (!StringHelper::endsWith($this->name, $this->domain_name)) {
                    $this->addError($attribute, Yii::t('hipanel:domain', 'To assign the IP, NS must be a child from main domain'));
                }
            }],
        ];
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'name' => Yii::t('hipanel:domain', 'Name server'),
            'ip' => Yii::t('hipanel:domain', 'IP'),
        ]);
    }
}
