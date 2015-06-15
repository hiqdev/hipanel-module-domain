<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\modules\domain\grid\DomainGridView;
use hipanel\widgets\Box;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

\yii\helpers\VarDumper::dump($model, 10, true);
\yii\helpers\VarDumper::dump($domainContactInfo, 10, true);

$this->title    = Html::encode($model->domain);
$this->subtitle = Yii::t('app','domain detailed information');
$this->breadcrumbs->setItems([
    ['label' => Yii::t('app', 'Domains'), 'url' => ['index']],
    $this->title,
]);
?>

<? Pjax::begin(Yii::$app->params['pjax']); ?>
    <div class="row" xmlns="http://www.w3.org/1999/html">
        <div class="col-md-3">
            <?php $box = Box::begin([
                'title' => $this->title,
                'options' => ['class' => 'box-solid'],
                'bodyOptions' => [
                    'class' => 'no-padding'
                ],
                'headerOptions' => [
                    'class' => 'with-border'
                ]
            ]); ?>
            <?= \yii\widgets\Menu::widget([

            ]) ?>
            <?php Box::end(); ?>
        </div>

        <div class="col-md-4">
            <?= DomainGridView::detailView([
                'model'   => $model,
                'columns' => [
                    'seller_id',
                    'client_id',
                    [
                        'attribute' => 'domain'
                    ],
                    'state',
                    'whois_protected',
                    'is_secured',
                    'nameservers',
                    'created_date',
                    'expires',
                    'autorenewal',
                ],
            ]) ?>
        </div>

        <div class="col-md-5">
            <div class="box box-success">
                <div class="box-header"><?= \Yii::t('app', 'Contacts') ?></div>
                <div class="box-body">
                </div>
            </div>
        </div>
    </div>
<?php Pjax::end();