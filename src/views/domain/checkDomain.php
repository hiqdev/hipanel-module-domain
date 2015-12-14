<?php

use hipanel\grid\GridView;
use hipanel\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Domain check');
$this->breadcrumbs->setItems([
    $this->title,
]);

$this->registerJs(<<<'JS'

;(function ($, window, document, undefined) {
    var pluginName = "domainsCheck",
		defaults = {
        domainRowClass: ".check-item",
			finally: function() {
                console.log('all done, sir!');
            }
		};

	function Plugin(element, options) {
        this.element = $(element);
        this.items = {};
		this.settings = $.extend({}, defaults, options);
		this.requests = {};
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}

	Plugin.prototype = {
        init: function () {
            this.items = this.element.find(this.settings.domainRowClass);
            setTimeout(function () {this.startQuerier();}.bind(this), 500);
		},
        startQuerier: function () {
            if (this.items) {
                $.each(this.items, function(k, item) {
                    this.query(item);
                }.bind(this));
			}
        },
        registerRequest: function(domain) {
            this.requests[domain] = {domain: domain, state: 'progress'};
		},
        registerFinish: function(domain) {
            this.requests[domain]['state'] = 'finished';

			if (this.registerCheckAll()) {
                return this.settings.finally();
            } else {
                return false;
            }
		},
        registerCheckAll: function() {
            if (this.requests.length == 0) return false;

            var all_closed = true;
            $.each(this.requests, function(domain, v) {
                if (v.state == 'progress') all_closed = false;
            });

            return all_closed;
        },
        query: function (item) {
            var domain = $(item).data('domain');

            if(!domain) return false;

            $.ajax({
				url: "/domain/domain/check",
				dataType: 'json',
				type: 'POST',
				beforeSend: function() {
                    this.registerRequest(domain);
                }.bind(this),
				data: {domain: domain},
				success: function(data) {
                    this.registerFinish(domain);
                    return this.settings.success(data, domain, this.element);
                }.bind(this)
			});

			return this;
		}
    };

	$.fn[ pluginName ] = function (options) {
        this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
        return this;
    };
})(jQuery, window, document);

//jQuery(document).on('pjax:complete', function() {
//    $('.domainsCheck').domainsCheck({
//        domainRowClass: '.check-item',
//        success: function(data, domain, element) {
//            console.log('123');
//            var $elem = $(element).find("tr[data-domain='" + domain + "']");
//            $elem.html(data);
//            return this;
//        },
//        finally: function() {
//            console.log('finally');
//        }
//    });
//});
JS
);
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-solid">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'check-domain',
                    'method' => 'get',
                    'options' => [
                        'data-pjax' => false,
                    ],
                    'fieldConfig' => [
                        'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    ],
//            'enableAjaxValidation' => true,
//            'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
                ]) ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= $form->field($model, 'domain')->textInput(['placeholder' => Yii::t('app', 'Domain search...'), 'class' => 'form-control input-lg']); ?>
                        </div>
                    </div>
                    <!-- /.col-md-8 -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= $form->field($model, 'zone')->dropDownList($dropDownZonesOptions, ['class' => 'form-control input-lg']); ?>
                        </div>
                    </div>
                    <!-- /.col-md-3 -->
                    <div
                        class="col-md-2"><?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-default btn-flat btn-lg btn-block']); ?></div>
                    <!-- /.col-md-1 -->
                </div>
                <!-- /.row -->
                <?php ActiveForm::end() ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
<?php if (!empty($results)) : ?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-solid">
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <div class="domain-list">
                    <?php foreach ($results as $line) : ?>
                        <div class="domain-line">
                            <div class="col-md-6">
                                <span class="domain-img">
                                    <i class="fa fa-globe fa-2x"></i>
                                </span>
                                <?php if ($model->is_available === false) : ?>
                                <span class="domain-name muted"><?= $line['domain'] ?></span><span
                                    class="domain-zone muted">.<?= $line['zone'] ?></span>
                                <?php else : ?>
                                    <span class="domain-name"><?= $line['domain'] ?></span><span
                                        class="domain-zone">.<?= $line['zone'] ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 text-center">
                            <span class="domain-price">
                                <?php if ($model->is_available === false) : ?>
                                    <span class="domain-taken">
                                        <?= Yii::t('app', 'Domain is not available') ?>
                                    </span>
                                <?php else : ?>
                                    <del>36.00€</del>
                                    34.00€
                                    <span class="domain-price-year">/year</span>
                                <?php endif; ?>
                            </span>
                            </div>
                            <div class="col-md-2">
                                <?php if ($model->is_available === false) : ?>
                                    <?= Html::a(Yii::t('app', 'WHOIS'), 'https://ahnames.com/ru/search/whois/#' . $line['domain'] . '.' . $line['zone'], ['target' => '_blank', 'class' => 'btn btn-default btn-flat']) ?>
                                <?php else : ?>
                                    <?= Html::a('<i class="fa fa-cart-plus fa-lg"></i>&nbsp; ' . Yii::t('app', 'Add to cart'), ['add-to-cart-registration', 'name' => $line['domain']], ['data-pjax' => 0, 'class' => 'btn btn-flat bg-olive']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
<?php endif; ?>
<style>
    .domain-line {
        border-bottom: 1px solid #f2f2f2;
        margin-bottom: 10px;
        line-height: 59px;
        height: 60px;
        font-size: 18px;
        -webkit-transition: border 0.25s;
        -moz-transition: border 0.25s;
        -o-transition: border 0.25s;
        transition: border 0.25s;
    }

    .domain-line:hover {
        border-color: #CCC;
        -webkit-transition: border 0.25s;
        -moz-transition: border 0.25s;
        -o-transition: border 0.25s;
        transition: border 0.25s;
    }

    .domain-line .domain-img {
        width: 48px;
        margin-left: 15px;
        line-height: 15px;
        color: grey;
    }

    .domain-line span {
        display: inline-block;
        vertical-align: middle;
        line-height: 59px;
    }

    .domain-line .domain-zone {
        font-weight: bold;
        text-transform: uppercase;
    }

    .domain-line .domain-price {
        color: gray;
    }

    .domain-line .domain-price .domain-price-year
    ,del {
        color: #ccc;
        font-size: 16px;
    }

    .domain-line .domain-taken {
        color: #ccc;
    }
    .domain-line .domain-whois {
        color: gray;
        font-size: 12px;
        line-height: 16px;
    }
    .domain-line .domain-name.muted,
    .domain-line .domain-zone.muted {
        color: #ccc;
    }
</style>