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

class Paidwp extends Model
{
    public function rules(): array
    {
        return [
            [['id', 'domain_id', 'days_left'], 'integer'],
            [['is_active', 'is_autorenewal'], 'boolean'],
            [['expires'], 'datetime'],
        ];
    }
}
