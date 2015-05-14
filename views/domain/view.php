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
use hipanel\modules\domain\widgets\GeoIP;

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
            <?php Box::begin(); ?>
                <p class="text-center">
                    <span class="profile-user-name"><?= $this->title; ?></span>
                            <?= GeoIP::widget([
                                'ip'        => gethostbyname($model->domain),
                            ]); ?>
                </p>
                <div class="profile-usermenu">
                    <ul class="nav">
                        <li>
                            <?= Html::a('<i class="ion-unlocked"></i>'.Yii::t('app', 'Freeze domain'), '#'); ?>
                        </li>
                        <li>
                            <?= Html::a('<i class="ion-network"></i>'.Yii::t('app', 'Push domain'), '#'); ?>
                        </li>
                        <li>
                            <?= Html::a('<i class="ion-loop"></i>'.Yii::t('app', 'Renew domain'), '#'); ?>
                        </li>
                        <li>
                            <?= Html::a('<i class="ion-unlocked"></i>'.Yii::t('app', 'Buy premium'), '#'); ?>
                        </li>
                        <li>
                            <?= Html::a('<i class="ion-card"></i>'.Yii::t('app', 'Change contacts'), '#'); ?>
                        </li>
                        <li>
                            <?= Html::a('<i class="ion-settings"></i>'.Yii::t('app', 'Change NSes'), '#'); ?>
                        </li>
                        <li>
                            <?= Html::a('<i class="ion-earth"></i>'.Yii::t('app', 'Change DNS records'), '#'); ?>
                        </li>
                        <li>
                            <?= Html::a('<i class="ion-hammer"></i>'.Yii::t('app', 'Create NSes'), '#'); ?>
                        </li>
                        <li>
                            <?= Html::a('<i class="ion-trash-a"></i>'.Yii::t('app', 'Delete domain'), '#'); ?>
                        </li>

                    </ul>
                </div>
            <?php Box::end(); ?>
        </div>

        <div class="col-md-4">
            <?= DomainGridView::detailView([
                'model'   => $model,
                'columns' => [
                    'seller_id','client_id',
                    ['attribute' => 'domain'],
                    'state',
                    'whois_protected','is_secured',
                    'nameservers',
                    'created_date','expires','autorenewal',
                ],
            ]) ?>
        </div>

        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header"><?= \Yii::t('app', 'Contacts') ?></div>
                <div class="box-body">
                </div>
            </div>
        </div>

    </div>
<?php Pjax::end();
