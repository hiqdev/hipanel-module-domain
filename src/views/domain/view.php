<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\modules\domain\grid\DomainGridView;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\domain\widgets\AuthCode;
use hipanel\widgets\Box;
use hipanel\widgets\Pjax;
use hiqdev\bootstrap_switch\BootstrapSwitch;
use hiqdev\xeditable\widgets\XEditable;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

//\yii\helpers\VarDumper::dump($model, 10, true);
//\yii\helpers\VarDumper::dump($domainContactInfo, 10, true);

$this->title    = Html::encode($model->domain);
$this->subtitle = Yii::t('app','domain detailed information') . ' #'.$model->id;
$this->breadcrumbs->setItems([
    ['label' => Yii::t('app', 'Domains'), 'url' => ['index']],
    $this->title,
]);
$this->registerCss(<<<CSS
.tab-pane {
    min-height: 300px;
}
CSS
);
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
                <img class="img-thumbnail" src="//mini.s-shot.ru/1024x768/PNG/200/Z100/?<?= $model->domain ?>"/>
            </div>
            <p class="text-center">
                <span class="profile-user-role"><?= $this->title ?></span>
                <br>
                <span class="profile-user-name"><?= $model->client . ' / ' . $model->seller ?></span>
            </p>

            <div class="profile-usermenu">
                <ul class="nav">
                    <li>
                        <?php Modal::begin([
                            'header' => '<h4 class="modal-title">' . Yii::t('app', 'Push ' . Html::tag('b', $this->title)) . '</h4>',
                            'footer' => Html::submitButton(Yii::t('app', 'Push'), ['class' => 'btn btn-default push' ]),
                            'toggleButton' => ['label' => '<i class="ion-ios-paperplane-outline"></i>' . Yii::t('app', 'Push domain'), 'tag' => 'a', 'class' => 'clickable'],
                        ]); ?>


                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'push-domain-form',
                        ]) ?>

                        <?= $form->field($model, 'client_id')->widget(\hipanel\modules\client\widgets\combo\ClientCombo::className())->hint(Yii::t('app', 'Client, you push your domain to')) ?>

                        <?php $form::end() ?>

                        <?php Modal::end(); ?>
                    </li>
                    <li>
                        <?= Html::a('<i class="ion-ios-loop-strong"></i>' . Yii::t('app', 'Synchronize data'), ['sync', 'id' => $model->id]); ?>
                    </li>
                </ul>
            </div>
            <?php Box::end(); ?>
        </div>

        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#domain-details" data-toggle="tab" ><?= Yii::t('app', 'Domain details') ?></a></li>
                    <li><a href="#ns-records" data-toggle="tab" ><?= Yii::t('app', 'NS records') ?></a></li>
<!--                    <li><a href="#authorization-code" data-toggle="tab">--><?//= Yii::t('app', 'Authorization code') ?><!--</a></li>-->
                    <li><a href="#dns-records" data-toggle="tab"><?= Yii::t('app', 'DNS records') ?></a></li>
<!--                    <li><a href="#url-forwarding" data-toggle="tab">--><?//= Yii::t('app', 'URL forwarding') ?><!--</a></li>-->
<!--                    <li><a href="#email-forwarding" data-toggle="tab">--><?//= Yii::t('app', 'Email forwarding') ?><!--</a></li>-->
<!--                    <li><a href="#parking" data-toggle="tab">--><?//= Yii::t('app', 'Parking') ?><!--</a></li>-->
                    <li><a href="#contacts" data-toggle="tab"><?= Yii::t('app', 'Contacts') ?></a></li>
                </ul>
                <div class="tab-content">

                    <!-- Morris t - Sales -->
                    <div class="tab-pane active" id="domain-details">
                        <?= DomainGridView::detailView([
                            'boxed' => false,
                            'model'   => $model,
                            'columns' => [
                                'seller_id', 'client_id',
                                [
                                    'attribute' => 'domain',
                                    'headerOptions' => ['class' => 'text-nowrap'],
                                ],
                                'note',
                                'state',
                                'whois_protected', 'is_secured',
                                'created_date', 'expires',
                                'autorenewal',
                                [
                                    'attribute' => 'authCode',
                                    'value' => function ($model){
                                        return AuthCode::widget(['domainId' => $model->id]);
                                    },
                                    'format' => 'raw'
                                ]
                            ],
                        ]) ?>

                    </div>

                    <!-- NS records -->
                    <div class=" tab-pane" id="ns-records">
                        <?php
//                        print DetailView::widget([
//                            'model' => $model,
//                            'attributes' => [
//                                [
//                                    'format' => 'raw',
//                                    'label' => 'Name Servers',
//                                    'value' => XEditable::widget([ // $model->nameservers
//                                        'model' => $model,
//                                        'attribute' => 'nameservers',
//                                        'pluginOptions' => [
//                                            'type' => 'textarea',
//                                            'emptytext' => Yii::t('app', 'There are no NS. Domain may not work properly'),
//                                            'url' => Url::to('setnote')
//                                        ]
//                                    ])
//                                ]
//                            ]
//                        ]);
                        ?>
                        <?= Html::tag('b', $model->getAttributeLabel('nameservers') . ': '); ?>
                        <?= XEditable::widget([
                            'model' => $model,
                            'attribute' => 'nameservers',
                            'pluginOptions' => [
                                'placement' => 'bottom',
                                'type' => 'textarea',
                                'emptytext' => Yii::t('app', 'There are no NS. Domain may not work properly'),
                                'url' => Url::to('set-ns')
                            ]
                        ]); ?>
                    </div>

                    <!-- Authorization code -->
<!--                    <div class=" tab-pane" id="authorization-code"></div>-->

                    <!-- DNS records -->
                    <div class=" tab-pane" id="dns-records">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'label' => Yii::t('app', 'Premium package'),
//                                    'value' => $model->is_premium == 't' ? Yii::t('app', 'Activated to ') . Yii::$app->formatter->asDatetime($model->prem_expires) : Yii::t('app', 'Not activated'),
                                    'value' => BootstrapSwitch::widget([
                                        'model' => $model,
                                        'attribute' => 'premium_autorenewal',
                                        'options' => [
                                            'label' => false
                                        ],
                                        'pluginOptions' => [
                                            'inlineLabel' => false,
                                            'labelText' => false
                                        ]
                                    ]),
                                    'format' => 'raw'
                                ],
                                [
                                    'label' => Yii::t('app', 'Premium package autorenewal'),
                                    'value' => BootstrapSwitch::widget([
                                        'model' => $model,
                                        'attribute' => 'premium_autorenewal',
                                        'options' => [
                                            'label' => false
                                        ],
                                        'pluginOptions' => [
                                            'inlineLabel' => false,
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
                            <div class="callout callout-warning">
                                <p><?= Yii::t('app', 'There is no DNS-records') ?></p>
                            </div>

                        <?php endif; ?>
                    </div>

                    <!-- URL forwarding -->
                    <div class=" tab-pane" id="url-forwarding"></div>

                    <!-- E-mail forwarding -->
                    <div class=" tab-pane" id="email-forwarding"></div>

                    <!-- Parking -->
                    <div class=" tab-pane" id="parking"></div>

                    <!--  -->
                    <div class=" tab-pane" id="contacts">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $this->render('_modalContacts', ['model' => $model]) ?>
                            </div>
                        <div id="contacts-tables">
                            <?= $this->render('_contactsTables', ['domainContactInfo' => $domainContactInfo]) ?>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>
