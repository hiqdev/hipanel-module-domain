<?php

/**
 * @var \hipanel\modules\domain\models\Domain
 */
use hipanel\modules\dns\widgets\DnsZoneEditWidget;
use hipanel\modules\domain\grid\DomainGridView;
use hipanel\modules\domain\menus\DomainDetailMenu;
use hipanel\modules\domain\widgets\AuthCode;
use hipanel\modules\domain\widgets\NsWidget;
use hipanel\widgets\Box;
use hipanel\widgets\ClientSellerLink;
use hipanel\widgets\Pjax;
use hiqdev\bootstrap_switch\BootstrapSwitchColumn;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

$model->nameservers = str_replace(',', ', ', $model->nameservers);

$this->title = Html::encode($model->domain);
$this->params['subtitle'] = Yii::t('hipanel:domain', 'Domain detailed information') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel', 'Domains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<'CSS'
.tab-pane {
    min-height: 300px;
}
.profile-usermenu > ul > li a:hover {
    background-color: #fafcfd;
    color: #5b9bd1;
}
.profile-usermenu > ul > li a {
    color: #93a3b5;
    font-size: 16px;
    font-weight: 400;
    position: relative;
    display: block;
    padding: 10px 15px;
}
CSS
);

?>

<?php Pjax::begin(Yii::$app->params['pjax']); ?>
<div class="row">

    <div class="col-md-3">
        <?php Box::begin([
            'options' => [
                'class' => 'box-solid',
            ],
            'bodyOptions' => [
                'class' => 'no-padding',
            ],
        ]); ?>
        <div class="profile-user-img text-center">
            <img class="img-thumbnail" src="//mini.s-shot.ru/1024x768/PNG/200/Z100/?<?= $model->domain ?>"/>
        </div>
        <p class="text-center">
            <span class="profile-user-role"><?= $this->title ?></span>
            <br>
            <span class="profile-user-name"><?= ClientSellerLink::widget(['model' => $model]) ?></span>
        </p>

        <div class="profile-usermenu">
            <?= DomainDetailMenu::widget(['model' => $model]) ?>
        </div>
        <?php Box::end() ?>
    </div>

    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#domain-details" data-toggle="tab"><?= Yii::t('hipanel:domain', 'Domain details') ?></a></li>
                <li><a href="#ns-records" data-toggle="tab"><?= Yii::t('hipanel:domain', 'NS records') ?></a></li>
                <li><a href="#dns-records" data-toggle="tab"><?= Yii::t('hipanel:domain', 'DNS records') ?></a></li>
                <li><a href="#contacts" data-toggle="tab"><?= Yii::t('hipanel', 'Contacts') ?></a></li>
            </ul>
            <div class="tab-content">

                <!-- Morris t - Sales -->
                <div class="tab-pane active" id="domain-details">
                    <?= DomainGridView::detailView([
                        'boxed' => false,
                        'model' => $model,
                        'columns' => [
                            'seller_id', 'client_id',
                            [
                                'attribute' => 'domain',
                                'headerOptions' => ['class' => 'text-nowrap'],
                            ],
                            'note',
                            'state',
                            'foa_sent_to',
                            'whois_protected_with_label',
                            'is_secured_with_label',
                            'created_date',
                            'expires',
                            'autorenewal_with_label',
                            [
                                'attribute' => 'authCode',
                                'label' => Yii::t('hipanel:domain', 'Authorization code'),
                                'value' => function ($model) {
                                    return AuthCode::widget(['model' => $model]);
                                },
                                'format' => 'raw',
                                'visible' => !$model->isRussianZones(),
                            ],
                        ],
                    ]) ?>

                </div>

                <!-- NS records -->
                <div class=" tab-pane" id="ns-records">
                    <?php /*
                    <?= Html::tag('b', $model->getAttributeLabel('nameservers') . ': '); ?>
                    <?= XEditable::widget([
                        'model' => $model,
                        'attribute' => 'nameservers',
                        'pluginOptions' => [
                            'placement' => 'bottom',
                            'type' => 'textarea',
                            'emptytext' => Yii::t('hipanel:domain', 'There are no NS. Domain may not work properly'),
                            'url' => Url::to('set-nss'),
                        ],
                    ]); ?>


                    */
                    ?>
                    <?= NsWidget::widget([
                        'model' => $model,
                        'attribute' => 'nsips',
                    ]); ?>

                </div>

                <!-- Authorization code -->
                <!--                    <div class=" tab-pane" id="authorization-code"></div>-->

                <!-- DNS records -->
                <div class="tab-pane" id="dns-records">
                    <?= DomainGridView::detailView([
                        'model' => $model,
                        'boxed' => false,
                        'dataProvider' => new ArrayDataProvider(['allModels' => [$config['model'], 'pagination' => false]]),
                        'columns' => [
                            'is_premium' => [
                                'label' => Yii::t('hipanel:domain', 'Premium package'),
                                'value' => function ($model) {
                                    $enablePremiumLink = Html::a(Yii::t('hipanel:domain', 'Enable premium'), Url::toRoute(''), ['class' => 'btn btn-success btn-xs pull-right']);

                                    return $model->is_premium === 't' ? Yii::t('hipanel', 'Activated to') . Yii::$app->formatter->asDatetime($model->prem_expires) : sprintf('%s %s', Yii::t('hipanel', 'Not enabled'), $enablePremiumLink);
                                },
                                'format' => 'raw',
                            ],
                            'premium_autorenewal' => [
                                'class' => BootstrapSwitchColumn::class,
                                'attribute' => 'premium_autorenewal',
                                'label' => Yii::t('hipanel:domain', 'Premium package autorenewal'),
                                'filter' => false,
                                'url' => Url::toRoute(['@hdomain/set-paid-feature-autorenewal']),
                                'popover' => Yii::t('hipanel:domain', 'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account'),
                                'visible' => $model->is_premium === 't' ? true : false,
                                'pluginOptions' => [
                                    'onColor' => 'info',
                                ],
                            ],
//                            [
//                                'label' => Yii::t('hipanel:domain', 'Premium package autorenewal'),
//                                'value' => BootstrapSwitch::widget([
//                                    'model' => $model,
//                                    'attribute' => 'premium_autorenewal',
//                                    'options' => [
//                                        'label' => false
//                                    ],
//                                    'pluginOptions' => [
//                                        'inlineLabel' => false,
//                                        'labelText' => false
//                                    ]
//                                ]),
//                                'format' => 'raw',
//                            ]
                        ],
                    ]); ?>
                    <?php if (Yii::$app->hasModule('dns')) {
                        echo DnsZoneEditWidget::widget([
                            'domainId' => $model->id,
                            'clientScriptWrap' => function ($js) {
                                return new \yii\web\JsExpression("
                                    $('a[data-toggle=tab]').filter(function () {
                                        return $(this).attr('href') == '#dns-records';
                                    }).on('shown.bs.tab', function (e) {
                                        $js
                                    });
                                ");
                            },
                        ]);
                    } ?>
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
                            <?= $this->render('_contacts', ['model' => $model]) ?>
                        </div>
                        <div id="contacts-tables">
                            <?= $this->render('_contactsTables', ['model' => $model]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
