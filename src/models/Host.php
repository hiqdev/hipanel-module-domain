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

use hipanel\helpers\StringHelper;
use hipanel\validators\DomainValidator;
use Yii;

class Host extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public function rules()
    {
        return [
            [['host'],                                  'safe'],
            [['id'],                                    'safe'],
            [['id'],                                    'integer', 'on' => 'delete'],
            [['seller_id', 'client_id', 'domain_id'],   'safe'],
            [['seller', 'client'],                      'safe'],
            [['domain', 'host'],                        'safe'],
            [['ip'],                                    'safe'],
            [['ips'],                                   'safe'],
            [['host', 'ips'], 'required', 'on' => 'create'],
            [['host'], DomainValidator::class],

            [['ips'], 'filter', 'filter' => function ($value) {
                if (!is_array($value)) {
                    return (mb_strlen($value) > 0) ? StringHelper::mexplode($value) : true;
                } else {
                    return $value;
                }
            }, 'on' => ['create', 'update']],

            [['ips'], 'each', 'rule' => ['ip'], 'on' => ['create', 'update']],
        ];
    }

}
