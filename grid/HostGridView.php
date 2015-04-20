<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\domain\grid;

use hipanel\grid\BoxedGridView;
use hipanel\grid\MainColumn;
use hipanel\grid\EditableColumn;
use Yii;

class HostGridView extends BoxedGridView
{
    static public function defaultColumns()
    {
        return [
            'host' => [
                'class'                 => MainColumn::className(),
                'filterAttribute'       => 'host_like',
            ],
            'ips' => [
                'class'                 => EditableColumn::className(),
                'filterAttribute'       => 'ips_like',
                'popover'               => Yii::t('app','Up to 13 IP addresses'),
                'action'                => ['update'],
            ],
        ];
    }
}
