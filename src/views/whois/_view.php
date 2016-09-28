<?php

/** @var string $sShotSrc */
/** @var \hipanel\modules\domain\models\Domain $model */

use yii\helpers\Html;

?>
<div class="row">
    <div class="col-md-3">
        <span class="mailbox-attachment-icon has-img">
            <?= Html::img($sShotSrc, ['alt' => $model->domain]) ?>
        </span>
        <div class="mailbox-attachment-info">
            <?= Html::a('<i class="fa fa-globe"></i>&nbsp;&nbsp;' . $model->domain, $model->domain, ['class' => 'mailbox-attachment-name']) ?>
        </div>
    </div>
    <div class="com-md-9">

    </div>
</div>

