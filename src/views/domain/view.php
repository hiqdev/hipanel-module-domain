<?php

/**
 * @var \yii\web\View $this
 * @var \hipanel\modules\domain\models\Domain $model
 * @var boolean $hasPincode
 * @var array $forwardingOptions
 */
use hipanel\modules\dns\widgets\DnsZoneEditWidget;
use hipanel\modules\domain\grid\DomainGridView;
use hipanel\modules\domain\menus\DomainDetailMenu;
use hipanel\modules\domain\widgets\AuthCode;
use hipanel\modules\domain\widgets\NsWidget;
use hipanel\widgets\ClientSellerLink;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

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

$this->registerJs(/** @lang ECMAScript 6 */
    <<<'JS'
function BackgroundNode({node, loadedClassName}) {
	let src = node.getAttribute('data-background-image-url');
	let show = (onComplete) => {
		requestAnimationFrame(() => {
			node.style.backgroundImage = `url(${src})`
			node.classList.add(loadedClassName);
			onComplete();
		})
	}

	return {
		node,

		// onComplete is called after the image is done loading.
		load: (onComplete) => {
			let img = new Image();
			img.onload = show(onComplete);
			img.src = src;
		}
	}
}

let defaultOptions = {
	selector: '[data-background-image-url]',
	loadedClassName: 'loaded'
}

function BackgroundLazyLoader({selector, loadedClassName} = defaultOptions) {
	let nodes = [].slice.apply(document.querySelectorAll(selector))
		.map(node => new BackgroundNode({node, loadedClassName}));

	let callback = (entries, observer) => {
		entries.forEach(({target, isIntersecting}) => {
			if (!isIntersecting) {
				return;
			}

			let obj = nodes.find(it => it.node.isSameNode(target));
			
			if (obj) {
				obj.load(() => {
					// Unobserve the node:
					observer.unobserve(target);
					// Remove this node from our list:
					nodes = nodes.filter(n => !n.node.isSameNode(target));
					
					// If there are no remaining unloaded nodes,
					// disconnect the observer since we don't need it anymore.
					if (!nodes.length) {
						observer.disconnect();
					}
				});
			}
		})
	};
	
	let observer = new IntersectionObserver(callback);
	nodes.forEach(node => observer.observe(node.node));
}

BackgroundLazyLoader();
JS
);

?>

<?php Pjax::begin(Yii::$app->params['pjax']); ?>
<div class="row">

    <div class="col-lg-3 col-md-6">

        <?php if ($model->canRenew()) : ?>
            <?= Html::a(Html::tag('i', null, ['class' => 'fa fa-fw fa-forward']) . Yii::t('hipanel:domain', 'Renew domain'),
                ['add-to-cart-renewal', 'model_id' => $model->id],
                ['class' => 'btn btn-success btn-block margin-bottom']
            ) ?>
        <?php endif; ?>

        <div class="box box-widget widget-user">
            <div class="widget-user-header" data-background-image-url="//mini.s-shot.ru/1024x768/PNG/500/Z100/?<?= $model->domain ?>"
                 style="background-position: top center; background-color: white; background-repeat:  no-repeat; background-size:  cover;">
            </div>
            <div class="box-footer" style="padding-top: 0;">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="description-block">
                            <h5 class="description-header" style="margin-bottom: .7rem; "><?= $this->title ?></h5>
                            <span class="description-text" style="text-transform: none;"><?= ClientSellerLink::widget(['model' => $model]) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer no-padding">
                <div class="profile-usermenu" style="margin-top: 0; padding-bottom: 0">
                    <?= DomainDetailMenu::widget(['model' => $model]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="box box-widget" id="domain-settings">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel:domain', 'Domain detailed information') ?></h3>
            </div>
            <div class="box-body no-padding table-responsive">
                <?= DomainGridView::detailView([
                    'boxed' => false,
                    'model' => $model,
                    'columns' => [
                        'seller_id',
                        'client_id',
                        [
                            'attribute' => 'domain',
                            'headerOptions' => ['class' => 'text-nowrap'],
                        ],
                        'note',
                        'state',
                        'whois_protected_with_label',
                        'is_secured_with_label',
                        'created_date',
                        'expires',
                        'autorenewal_with_label',
                        [
                            'attribute' => 'authCode',
                            'label' => Yii::t('hipanel:domain', 'Authorization code'),
                            'value' => function ($model) use ($hasPincode) {
                                return AuthCode::widget([
                                    'model' => $model,
                                    'askPincode' => $hasPincode,
                                ]);
                            },
                            'format' => 'raw',
                            'visible' => !$model->isRussianZone(),
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-md-12">
        <div class="box box-widget">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel:client', 'Contacts') ?></h3>
                <div class="box-tools pull-right">
                    <?= $this->render('_contactButtons', ['model' => $model]) ?>
                </div>
            </div>
            <div class="box-body no-padding">
                <div class="box-footer box-comments">
                    <?= $this->render('_contactsTables', ['model' => $model]) ?>
                </div>
            </div>
        </div>
    </div>
    <?php /*
        <div class="nav-tabs-custom">
            <ul id="domain-details-tab" class="nav nav-tabs">
                <li class="active">
                    <a href="#domain-details" data-toggle="tab"><?= Yii::t('hipanel:domain', 'Domain details') ?></a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <?= Yii::t('hipanel:domain', 'Premium package') ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#premium" role="tab" data-toggle="tab">
                                <?= Yii::t('hipanel:domain', 'Manage Premium') ?>
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="#url-forwarding" role="tab" data-toggle="tab">
                                <?= Yii::t('hipanel:domain', 'URL forwarding') ?>
                            </a>
                        </li>
                        <li>
                            <a href="#email-forwarding" role="tab" data-toggle="tab">
                                <?= Yii::t('hipanel:domain', 'Email forwarding') ?>
                            </a>
                        </li>
                        <li>
                            <a href="#parking" role="tab" data-toggle="tab">
                                <?= Yii::t('hipanel:domain', 'Parking') ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <li><a href="#ns-records" data-toggle="tab"><?= Yii::t('hipanel:domain', 'NS records') ?></a></li>
                <li><a href="#dns-records" data-toggle="tab"><?= Yii::t('hipanel:domain', 'DNS records') ?></a></li>
                <li><a href="#contacts" data-toggle="tab"><?= Yii::t('hipanel', 'Contacts') ?></a></li>
            </ul>
            <div class="tab-content">

                <?= $this->render('_premiumTabs', compact('model', 'forwardingOptions')) ?>

                <div class="tab-pane active" id="domain-details">
                    <?= DomainGridView::detailView([
                        'boxed' => false,
                        'model' => $model,
                        'columns' => [
                            'seller_id',
                            'client_id',
                            [
                                'attribute' => 'domain',
                                'headerOptions' => ['class' => 'text-nowrap'],
                            ],
                            'note',
                            'state',
                            'whois_protected_with_label',
                            'is_secured_with_label',
                            'created_date',
                            'expires',
                            'autorenewal_with_label',
                            [
                                'attribute' => 'authCode',
                                'label' => Yii::t('hipanel:domain', 'Authorization code'),
                                'value' => function ($model) use ($hasPincode) {
                                    return AuthCode::widget([
                                        'model' => $model,
                                        'askPincode' => $hasPincode,
                                    ]);
                                },
                                'format' => 'raw',
                                'visible' => !$model->isRussianZone(),
                            ],
                        ],
                    ]) ?>
                </div>

                <!-- NS records -->
                <div class=" tab-pane" id="ns-records">
                    <?= NsWidget::widget([
                        'model' => $model,
                        'attribute' => 'nsips',
                    ]); ?>
                </div>

                <!-- DNS records -->
                <div class="tab-pane" id="dns-records">
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

                <div class="tab-pane" id="contacts">
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
        */ ?>
</div>
</div>
<div class="row">
    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
        <div class="nav-tabs-custom">
            <ul id="domain-details-tab" class="nav nav-tabs">
                <li class="active"><a href="#ns-records"
                                      data-toggle="tab"><?= 'Name servers' ?></a></li>
                <li><a href="#dns-records" data-toggle="tab"><?= Yii::t('hipanel:domain', 'DNS records') ?></a></li>
            </ul>
            <div class="tab-content">

                <!-- NS records -->
                <div class="tab-pane active" id="ns-records">
                    <?= NsWidget::widget([
                        'model' => $model,
                        'attribute' => 'nsips',
                    ]); ?>
                </div>

                <!-- DNS records -->
                <div class="tab-pane" id="dns-records">
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
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
        <div class="nav-tabs-custom">
            <ul id="domain-details-tab" class="nav nav-tabs">
                <li class="active">
                    <a href="#premium" role="tab" data-toggle="tab">
                        <?= Yii::t('hipanel:domain', 'Manage Premium') ?>
                    </a>
                </li>
                <li>
                    <a href="#url-forwarding" role="tab" data-toggle="tab">
                        <?= Yii::t('hipanel:domain', 'URL forwarding') ?>
                    </a>
                </li>
                <li>
                    <a href="#email-forwarding" role="tab" data-toggle="tab">
                        <?= Yii::t('hipanel:domain', 'Email forwarding') ?>
                    </a>
                </li>
                <li>
                    <a href="#parking" role="tab" data-toggle="tab">
                        <?= Yii::t('hipanel:domain', 'Parking') ?>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <?= $this->render('_premiumTabs', compact('model', 'forwardingOptions')) ?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
