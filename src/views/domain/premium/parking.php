<?php

use hipanel\modules\domain\models\Parking;
use hipanel\modules\domain\widgets\PremiumAlert;
use hipanel\widgets\Pjax;
use yii\bootstrap\Html;
use yii\widgets\DetailView;

?>
<?php Pjax::begin([
    'id' => 'park_view',
    'enablePushState' => false,
]) ?>

<?php if ($model->premium->is_active) : ?>
    <?= $this->render('_formParking', ['model' => $model->parking ?: new Parking(), 'domain' => $model]) ?>
<?php else : ?>
    <?= PremiumAlert::widget() ?>
<?php endif; ?>

<?php if ($model->park) : ?>
    <hr>
    <?= DetailView::widget([
        'model' => $model->parking,
        'attributes' => [
            [
                'attribute' => 'type_id',
                'format' => 'html',
                'value' => Html::img($model->parking->skinImage, ['width' => 130]),
            ],
            'title',
            'siteheader',
            'sitetext',
        ],
    ]) ?>
<?php endif; ?>

<?php Pjax::end() ?>
