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

use hipanel\base\Model;
use hipanel\helpers\StringHelper;
use hipanel\modules\dns\validators\FqdnValueValidator;
use hipanel\validators\DomainValidator;
use Yii;

class Ns extends Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['name', 'domain_name'], 'filter', 'filter' => 'trim', 'skipOnEmpty' => true],
            [['name'],  FqdnValueValidator::className()],
            [['ip'], 'filter', 'filter' => function ($value) {
                return StringHelper::explode($value, ',', true, true);
            }, 'skipOnArray' => true, 'skipOnEmpty' => true],
            [['ip'], 'each', 'rule' => ['ip']],
            [['ip'],  function ($attribute, $params) {
                if (!StringHelper::endsWith(DomainValidator::convertIdnToAscii($this->name), DomainValidator::convertIdnToAscii($this->domain_name))) {
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
