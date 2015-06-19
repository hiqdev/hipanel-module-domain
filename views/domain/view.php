<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\modules\domain\grid\DomainGridView;
use hipanel\widgets\Box;
use hipanel\widgets\Pjax;
use hiqdev\xeditable\widgets\XEditable;
use Yii;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

//\yii\helpers\VarDumper::dump($model, 10, true);
//\yii\helpers\VarDumper::dump($domainContactInfo, 10, true);

$this->title    = Html::encode($model->domain);
$this->subtitle = Yii::t('app','domain detailed information');
$this->breadcrumbs->setItems([
    ['label' => Yii::t('app', 'Domains'), 'url' => ['index']],
    $this->title,
]);
?>

<? Pjax::begin(Yii::$app->params['pjax']); ?>
    <div class="row" xmlns="http://www.w3.org/1999/html">
        <!--div class="col-md-3">
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
        </div-->
        <div class="col-md-3">
            <?php Box::begin([
                'options' => [
                    'class' => 'box-solid',
                ],
                'bodyOptions' => [
                    'class' => 'no-padding'
                ]
            ]); ?>
            <div class="profile-user-img text-center">
                <i class="ion-ios-world-outline" style="font-size: 95px;"></i>
            </div>
            <p class="text-center">
                <span class="profile-user-name"><?= $this->title; ?></span>
                <br>
                <span class="profile-user-role"><?= $model->zone; ?></span
            </p>

            <div class="profile-usermenu">
                <ul class="nav">
                    <li>
                        <?= Html::a('<i class="ion-unlocked"></i>' . Yii::t('app', 'Authorization code'), '#'); ?>
                    </li>
                    <li>
                        <?= Html::a('<i class="ion-lock-combination"></i>' . Yii::t('app', 'Change authorization code'), '#'); ?>
                    </li>
                </ul>
            </div>
            <?php Box::end(); ?>
        </div>

        <div class="col-md-9">
            <div class="nav-tabs-custom" style="cursor: move;">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#domain-details" data-toggle="tab" ><?= Yii::t('app', 'Domain details') ?></a></li>
                    <li><a href="#ns-records" data-toggle="tab" ><?= Yii::t('app', 'NS records') ?></a></li>
<!--                    <li><a href="#authorization-code" data-toggle="tab">--><?//= Yii::t('app', 'Authorization code') ?><!--</a></li>-->
                    <li><a href="#dns-records" data-toggle="tab"><?= Yii::t('app', 'DNS records') ?></a></li>
                    <li><a href="#url-forwarding" data-toggle="tab"><?= Yii::t('app', 'URL forwarding') ?></a></li>
                    <li><a href="#email-forwarding" data-toggle="tab"><?= Yii::t('app', 'Email forwarding') ?></a></li>
                    <li><a href="#parking" data-toggle="tab"><?= Yii::t('app', 'Parking') ?></a></li>
                    <li><a href="#contacts" data-toggle="tab"><?= Yii::t('app', 'Contacts') ?></a></li>
                </ul>
                <div class="tab-content no-padding">

                    <!-- Morris chart - Sales -->
                    <div class="chart tab-pane active" id="domain-details">
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
                                'created_date',
                                'expires',
                                'autorenewal',
                            ],
                        ]) ?>
                    </div>

                    <!-- NS records -->
                    <div class="chart tab-pane" id="ns-records">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'format' => 'raw',
                                    'label' => 'Name Servers',
                                    'value' => XEditable::widget([ // $model->nameservers
                                        'model' => $model,
                                        'attribute' => 'nameservers',
                                        'pluginOptions' => [
                                            'type' => 'textarea',
                                            'emptytext' => Yii::t('app', 'There are no NS. Domain may not work properly'),
                                            'url' => Url::to('setnote')
                                        ]
                                    ])
                                ]
                            ]
                        ]); ?>
                    </div>

                    <!-- Authorization code -->
<!--                    <div class="chart tab-pane" id="authorization-code"></div>-->

                    <!-- DNS records -->
                    <div class="chart tab-pane" id="dns-records">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'label' => Yii::t('app', 'Premium package'),
                                    'value' => $model->is_premium == 't' ? Yii::t('app', 'Activated to ') . Yii::$app->formatter->asDatetime($model->prem_expires) : Yii::t('app', 'Not activated'),
                                    'format' => 'raw'
                                ],
                                [
                                    'label' => Yii::t('app', 'Premium package autorenewal'),
                                    'value' => \hiqdev\bootstrap_switch\BootstrapSwitch::widget([
                                        'model' => $model,
                                        'attribute' => 'premium_autorenewal',
                                        'pluginOptions' => [
                                            'labelText' => false
                                        ]
                                    ]),
                                    'format' => 'raw',
                                ]
                            ]
                        ]); ?>
                        <?php if (!empty($model->dns)) : ?>
                            <?= GridView::widget([
                                'dataProvider' => (new \yii\data\ArrayDataProvider([
                                    'allModels' => $model->dns
                                ])),
                                'layout' => "{items}\n{pager}",
                                'columns' => [
                                    [
                                        'attribute' => 'name',
                                        'label' => Yii::t('app', 'Domain'),
                                    ],
                                    [
                                        'attribute' => 'type',
                                        'value' => function($model, $key, $index, $column) {
                                            return mb_strtoupper($model['type']);
                                        }
                                    ],
                                    [
                                        'attribute' => 'value',
                                        'label' => false,
                                    ],
                                ]
                            ]); ?>
                        <?php else : ?>
                            <?= Yii::t('app', 'There is no DNS-records') ?>
                        <?php endif; ?>
                    </div>

                    <!-- URL forwarding -->
                    <div class="chart tab-pane" id="url-forwarding"></div>

                    <!-- E-mail forwarding -->
                    <div class="chart tab-pane" id="email-forwarding"></div>

                    <!-- Parking -->
                    <div class="chart tab-pane" id="parking"></div>

                    <!--  -->
                    <div class="chart tab-pane" id="contacts"></div>

                </div>
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>