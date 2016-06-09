<?php

use hipanel\assets\HipanelAsset;
use hipanel\assets\IsotopeAsset;
use hipanel\helpers\Url;
use hipanel\modules\domain\assets\DomainCheckPluginAsset;
use hipanel\modules\domain\models\Domain;
use hiqdev\combo\StaticCombo;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

DomainCheckPluginAsset::register($this);
IsotopeAsset::register($this);
HipanelAsset::register($this);

$this->title = Yii::t('hipanel/domainchecker', 'Domain check');
$this->breadcrumbs->setItems([
    $this->title,
]);
$requestedDomain = implode('.', Yii::$app->request->get('Domain') ?: []);
$model->domain = empty($model->domain) ? Yii::$app->request->get('domain-check') : $model->domain;
$this->registerCss('
.nav-stacked > li.active > a, .nav-stacked > li.active > a:hover {
    background: transparent!important;
    color: #444!important;
}

/* Fix Select2 border radius */
.input-group-lg .select2-container .select2-choice, .select2-container.input-lg .select2-choice {
    border-radius: 0;
}

select2-container .select2-choice, .select2-container .select2-choices, .select2-container .select2-choices .select2-search-field input, .select2-search input {
    border-radius: 0px;
}

.domain-list .btn {
    width: 100%;
}

.domain-line {
    border-bottom: 1px solid #f2f2f2;
    /*margin-bottom: 10px;*/
    line-height: 59px;
    height: 60px;
    font-size: 18px;
    -webkit-transition: border 0.25s;
    -moz-transition: border 0.25s;
    -o-transition: border 0.25s;
    transition: border 0.25s;
    width: 100%;
}

.domain-iso-line {
    width: 100%;
    clear: both;
}

.domain-list:after {
    content: \'\';
    display: block;
    clear: both;
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

.domain-line .domain-price .domain-price-year, del {
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
');
if (!empty($results)) {
    $this->registerJs(<<<'JS'
    $(document).on('click', 'a.add-to-cart-button', function(event) {
        event.preventDefault();
        var addToCartElem = $(this);
        addToCartElem.button('loading');
        $.post(addToCartElem.data('domain-url'), function() {
            Hipanel.updateCart(function() {
                addToCartElem.button('complete');
                setTimeout(function () {addToCartElem.addClass('disabled')}, 0);
            });
        });

        return false;
    });

    $.fn.isOnScreen = function(x, y){

        if(x == null || typeof x == 'undefined') x = 1;
        if(y == null || typeof y == 'undefined') y = 1;

        var win = $(window);

        var viewport = {
            top : win.scrollTop(),
            left : win.scrollLeft()
        };
        viewport.right = viewport.left + win.width();
        viewport.bottom = viewport.top + win.height();

        var height = this.outerHeight();
        var width = this.outerWidth();

        if(!width || !height){
            return false;
        }

        var bounds = this.offset();
        bounds.right = bounds.left + width;
        bounds.bottom = bounds.top + height;

        var visible = (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

        if(!visible){
            return false;
        }

        var deltas = {
            top : Math.min( 1, ( bounds.bottom - viewport.top ) / height),
            bottom : Math.min(1, ( viewport.bottom - bounds.top ) / height),
            left : Math.min(1, ( bounds.right - viewport.left ) / width),
            right : Math.min(1, ( viewport.right - bounds.left ) / width)
        };

        return (deltas.left * deltas.right) >= x && (deltas.top * deltas.bottom) >= y;
    };

    $('.domain-list').domainsCheck({
        domainRowClass: '.domain-line',
        success: function(data, domain, element) {
            var $elem = $(element).find("div[data-domain='" + domain + "']");
            var $parentElem = $(element).find("div[data-domain='" + domain + "']").parents('div.domain-iso-line').eq(0);
            $elem.replaceWith($(data).find('.domain-line'));
            $parentElem.attr('class', $(data).attr('class'));

            return this;
        },
        beforeQueryStart: function (item) {
            var $item = $(item);
            if ($item.isOnScreen() && !$item.hasClass('checked') && $item.is(':visible')) {
                $item.addClass('checked');
                return true;
            }

            return false;
        },
        finally: function () {
            // init Isotope
            var grid = $('.domain-list').isotope({
                itemSelector: '.domain-iso-line',
                layout: 'vertical',
                // disable initial layout
                isInitLayout: false
            });
            //grid.isotope({ filter: '.popular' });
            // bind event
            grid.isotope('on', 'arrangeComplete', function () {
                $('.domain-iso-line').css({'visibility': 'visible'});
                $('.domain-list').domainsCheck().startQuerier();
            });
            // manually trigger initial layout
            grid.isotope();
            // store filter for each group
            var filters = {};
            $('.filters').on('click', 'a', function(event) {
                // get group key
                var $buttonGroup = $(this).parents('.nav');
                var $filterGroup = $buttonGroup.attr('data-filter-group');
                // set filter for group
                filters[$filterGroup] = $(this).attr('data-filter');
                // combine filters
                var filterValue = concatValues(filters);
                // set filter for Isotope
                grid.isotope({filter: filterValue});
            });
            // change is-checked class on buttons
            $('.nav').each(function(i, buttonGroup) {
                $(buttonGroup).on( 'click', 'a', function(event) {
                    $(buttonGroup).find('.active').removeClass('active');
                    $(this).parents('li').addClass('active');
                });
            });
            // flatten object by concatting values
            function concatValues(obj) {
                var value = '';
                for (var prop in obj) {
                    value += obj[prop];
                }

                return value;
            }
        }
    });

    $( document ).on( "scroll", function() {
        $('.domain-list').domainsCheck().startQuerier();
    });
JS
    );
}
?>

<div class="row">
    <div class="col-md-3 filters">

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel/domainchecker', 'Status') ?></h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked" data-filter-group="status">
                    <li class="active"><a href="#" data-filter=""><?= Yii::t('hipanel/domainchecker', 'All') ?></a></li>
                    <li><a href="#" data-filter=".available"><?= Yii::t('hipanel/domainchecker', 'Available') ?></a></li>
                    <li><a href="#" data-filter=".unavailable"><?= Yii::t('hipanel/domainchecker', 'Unavailable') ?></a>
                    </li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel/domainchecker', 'Special') ?></h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked" data-filter-group="special">
                    <li class="active"><a href="#" data-filter=""><?= Yii::t('hipanel/domainchecker', 'All') ?></a></li>
                    <li><a href="#" data-filter=".popular"><?= Yii::t('hipanel/domainchecker', 'Popular Domains') ?></a></li>
                    <li><a href="#" data-filter=".promotion"><?= Yii::t('hipanel/domainchecker', 'Promotion') ?></a></li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel/domainchecker', 'Categories') ?></h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked" data-filter-group="categories">
                    <li class="active">
                        <a href="#" data-filter=""><?= Yii::t('hipanel/domainchecker', 'All') ?>
                            <span class="label label-default pull-right"><?= count($results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".adult"><?= Yii::t('hipanel/domainchecker', 'Adult') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('adult', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".geo"><?= Yii::t('hipanel/domainchecker', 'GEO') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('geo', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".general"><?= Yii::t('hipanel/domainchecker', 'General') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('general', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".nature"><?= Yii::t('hipanel/domainchecker', 'Nature') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('nature', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".internet"><?= Yii::t('hipanel/domainchecker', 'Internet') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('internet', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".sport"><?= Yii::t('hipanel/domainchecker', 'Sport') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('sport', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".society"><?= Yii::t('hipanel/domainchecker', 'Society') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('society', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".audio_music"><?= Yii::t('hipanel/domainchecker', 'Audio&Music') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('audio_music', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".home_gifts"><?= Yii::t('hipanel/domainchecker', 'Home&Gifts') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('home_gifts', $results) ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>

    </div>

    <div class="col-md-9">

        <div class="row">
            <div class="col-md-12">
                <?php if (empty($dropDownZonesOptions)) : ?>
                    <div class="alert alert-warning alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <strong><?= Yii::t('hipanel/domainchecker', 'There are no available domain zones') ?>!</strong>
                    </div>
                <?php endif; ?>
                <div class="box box-solid">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'check-domain',
                            'action' => Url::to(['@domainchecker/check-domain']),
                            'method' => 'get',
                            'options' => [
                                'data-pjax' => false,
                            ],
                            'fieldConfig' => [
                                'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                            ],
                        ]) ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= $form->field($model, 'domain')->textInput([
                                        'placeholder' => Yii::t('hipanel/domainchecker', 'Domain name'),
                                        'class' => 'form-control input-lg',
                                        'name' => 'domain',
                                    ]); ?>
                                </div>
                            </div>
                            <!-- /.col-md-8 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= $form->field($model, 'zone')->widget(StaticCombo::classname(), [
                                        'data' => $dropDownZonesOptions,
                                        'hasId' => true,
                                        'inputOptions' => [
                                            'value' => $model->zone !== null ? $model->zone : $model::DEFAULT_ZONE,
                                            'class' => 'form-control input-lg',
                                            'name' => 'zone',
                                        ],
                                    ]); ?>
                                </div>
                            </div>
                            <!-- /.col-md-3 -->
                            <div
                                class="col-md-2"><?= Html::submitButton(Yii::t('hipanel/domainchecker', 'Search'), ['class' => 'btn btn-info btn-flat btn-lg btn-block']); ?></div>
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
            <div class="box box-solid">
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="domain-list">
                        <?php foreach ($results as $line) : ?>
                            <?= $this->render('_checkDomainLine', ['line' => $line, 'requestedDomain' => $requestedDomain]) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        <?php endif; ?>
    </div>
</div>
