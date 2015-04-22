<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\modules\domain\grid\DomainGridView;
use hipanel\widgets\Box;
use hipanel\widgets\RequestState;
use hipanel\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Json;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;

$this->title                   = Html::encode($model->domain);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Domains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<? Pjax::begin(Yii::$app->params['pjax']); ?>
    <div class="row" xmlns="http://www.w3.org/1999/html">

        <div class="col-md-3">
            <?php Box::begin(); ?>
                <p class="text-center">
                    <span class="profile-user-name"><?= $this->title; ?></span>
                    <?php if (function_exists('geoip_record_by_name')) : ?>
                        <?php if ($iprecord = @geoip_record_by_name(gethostbyname($model->domain))) : ?>
                            <?= (new Map([
                                'center'    => new LatLng([
                                    'lat'       => $iprecord['latitude'],
                                    'lng'       => $iprecord['longitude'],
                                ]),
                                'zoom'  => 9,
                                'width' => '100%',
                                'height'=> 200,
                            ]))->display(); ?>
                        <?php endif; ?>
                    <?php endif; ?>
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
