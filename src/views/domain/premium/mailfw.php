<?php

use hipanel\modules\domain\grid\MailfwGridView;
use hipanel\modules\domain\models\Mailfw;
use hipanel\widgets\Pjax;
use yii\data\ArrayDataProvider;

?>
<?php Pjax::begin([
    'id' => 'mail_fw_view',
    'enablePushState' => false,
]) ?>

<?= $this->render('_formMailfw', ['model' => new Mailfw, 'domain' => $model]) ?>
<hr>
<?= MailfwGridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->mailfws,
        'modelClass' => Mailfw::class,
        'pagination' => ['pageSize' => count($model->mailfws)],
    ]),
    'emptyText' => Yii::t('hipanel:domain', 'E-mail forwarding is not configured'),
    'domain' => $model->domain,
    'is_premium' => $model->premium->is_active,
    'columns' => ['name', 'value', 'actions'],
]) ?>

<?php Pjax::end() ?>
