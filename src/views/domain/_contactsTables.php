<?php
use yii\widgets\DetailView;
use hipanel\modules\domain\models\Domain;
use yii\helpers\Html;

?>


<?php foreach (Domain::$contactOptions as $item) : ?>
    <div class="col-md-6">
        <?= Html::tag('h3', ucfirst($item) . ' ' . Yii::t('app', 'contact')); ?>
        <?= DetailView::widget([
            'model' => $domainContactInfo[$item],
            'attributes' => [
                'name',
                'email',
                'organization',
                'voice_phone',
                'fax_phone',
            ]
        ]); ?>
    </div>
<?php endforeach; ?>