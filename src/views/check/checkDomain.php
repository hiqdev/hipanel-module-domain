<?php

use hipanel\assets\IsotopeAsset;
use hipanel\helpers\Url;
use hipanel\modules\domain\assets\DomainCheckPluginAsset;
use hipanel\modules\domain\models\Domain;
use hiqdev\assets\icheck\iCheckAsset;
use hiqdev\combo\StaticCombo;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var \hipanel\modules\domain\forms\CheckForm $model */
/** @var array $dropDownZonesOptions */
/** @var \hipanel\modules\domain\forms\CheckForm[] $results */
DomainCheckPluginAsset::register($this);
IsotopeAsset::register($this);
iCheckAsset::register($this);
$this->registerJs("
    $('.icheck input').iCheck({
        labelHover: false,
        cursor: true,
        handle: 'checkbox',
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
");

$this->title = Yii::t('hipanel:domain', 'Domain check');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
.select2-container--default .select2-selection--single, .select2-selection .select2-selection--single.input-lg {
    height: 46px;
    padding: 10px 16px;
    font-size: 18px;
    line-height: 1.3333333;
}
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


.filter-suggestions li {
    border: none;
    text-align: left;
    border-bottom: 1px solid #f4f4f4;
    border-left: 3px solid transparent;
    color: #444;
    margin: 0;
    position: relative;
    display: block;
    background-color: transparent;
    padding: 10px 15px;
    width: 100%;
    outline: none;
}

.filter li b {

}

.filter-suggestions li span {
    color: #777;
    display: block;
    font-size: smaller;
    padding-top: 1rem;
}

.nav-tabs-custom > .tab-content {
    padding: 10px 0;
}

');

if (!empty($results)) {
    $this->registerJs(<<<'JS'
    
    $(document).on('click', 'checkbox', function() {
        $('.suggestion').css({'display': 'block'});  
    });

    $(document).on('click', 'a.add-to-cart-button', function(event) {
        event.preventDefault();
        var addToCartElem = $(this);
        addToCartElem.button('loading');
        $.post(addToCartElem.data('domain-url'), function() {
            hipanel.updateCart(function() {
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
            $('#domain-tabs a').on('shown.bs.tab', function (e) {
                $('.filters .nav li.active a').click(); // FIX remove not filtred items
            });
            $('.filters .nav li.active a').click(); // FIX remove not filtred items

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
                // filter: '.promotion'
            });
            // grid.isotope({ filter: '.popular' });
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
            $('.filters .nav').each(function(i, buttonGroup) {
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
                <h3 class="box-title"><?= Yii::t('hipanel:domain', 'Status') ?></h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked" data-filter-group="status">
                    <li class="active"><a href="#" data-filter=""><?= Yii::t('hipanel:domain', 'All') ?></a></li>
                    <li><a href="#" data-filter=".available"><?= Yii::t('hipanel:domain', 'Available') ?></a></li>
                    <li><a href="#" data-filter=".unavailable"><?= Yii::t('hipanel:domain', 'Unavailable') ?></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel:domain', 'Special') ?></h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked" data-filter-group="special">
                    <li class="active"><a href="#" data-filter=""><?= Yii::t('hipanel:domain', 'All') ?></a></li>
                    <li><a href="#" data-filter=".popular"><?= Yii::t('hipanel:domain', 'Popular Domains') ?></a></li>
                    <li><a href="#" data-filter=".promotion"><?= Yii::t('hipanel:domain', 'Promotion') ?></a></li>
                </ul>
            </div>
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel:domain', 'Categories') ?></h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked" data-filter-group="categories">
                    <li class="active">
                        <a href="#" data-filter=""><?= Yii::t('hipanel:domain', 'All') ?>
                            <span class="label label-default pull-right"><?= count($results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".general"><?= Yii::t('hipanel:domain', 'General') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('general', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".internet"><?= Yii::t('hipanel:domain', 'Internet') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('internet', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".sport"><?= Yii::t('hipanel:domain', 'Sport') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('sport', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".society"><?= Yii::t('hipanel:domain', 'Society') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('society', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".geo"><?= Yii::t('hipanel:domain', 'GEO') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('geo', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".nature"><?= Yii::t('hipanel:domain', 'Nature') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('nature', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".audio_music"><?= Yii::t('hipanel:domain', 'Audio&Music') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('audio_music', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".home_gifts"><?= Yii::t('hipanel:domain', 'Home&Gifts') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('home_gifts', $results) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-filter=".adult"><?= Yii::t('hipanel:domain', 'Adult') ?>
                            <span class="label label-default pull-right"><?= Domain::getCategoriesCount('adult', $results) ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <div class="col-md-9">

        <div class="row">
            <div class="col-md-12">
                <?php if (empty($dropDownZonesOptions)) : ?>
                    <div class="alert alert-warning alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <strong><?= Yii::t('hipanel:domain', 'There are no available domain zones') ?>!</strong>
                    </div>
                <?php endif; ?>
                <div class="box box-solid">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'check-domain',
                            'action' => Url::to(['/domain/check/check-domain']),
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
                                    <?= $form->field($model, 'fqdns')->textInput([
                                        'placeholder' => Yii::t('hipanel:domain', 'Domain name'),
                                        'class' => 'form-control',
                                        'name' => 'fqdns',
                                        'value' => $model->fqdnsInline,
                                    ])->hint(Yii::t('hipanel:domain', 'You can search by multiple domains for availability. Use a space, comma, semicolon or newline for word separation.')); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= $form->field($model, 'zones')->widget(StaticCombo::class, [
                                        'data' => $dropDownZonesOptions,
                                        'hasId' => true,
                                        'multiple' => true,
                                        'inputOptions' => [
                                            'value' => $model->zones,
                                            'class' => 'form-control',
                                            'name' => 'zones[]',
                                        ],
                                        'pluginOptions' => [
                                            'select2Options' => [
                                                'placeholder' => '.com',
                                            ],
                                        ],
                                    ]); ?>
                                </div>
                            </div>
                            <div class="col-md-2"><?= Html::submitButton(Yii::t('hipanel:domain', 'Search'), ['class' => 'btn btn-info btn-flat btn-block']); ?></div>
                        </div>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($results)) : ?>
            <div class="box box-solid">
                <div class="box-body no-padding nav-tabs-custom">

                    <ul id="domain-tabs" class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#commons" aria-controls="home" role="tab" data-toggle="tab"><?= Yii::t('hipanel:domain', 'Exact search') ?></a>
                        </li>
                        <li role="presentation">
                            <a href="#suggestions" aria-controls="profile" role="tab" data-toggle="tab"><?= Yii::t('hipanel:domain', 'Similar domains') ?></a>
                        </li>
                    </ul>

                    <div class="tab-content domain-list">

                        <div role="tabpanel" class="tab-pane active" id="commons">
                            <?php foreach ($results as $model) : ?>
                                <?php if (!$model->isSuggestion) : ?>
                                    <?= $this->render('_checkDomainItem', ['model' => $model]) ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="suggestions">
                            <?php $suggestions = array_filter($results, function (\hipanel\modules\domain\forms\CheckForm $model) {
                                return $model->isSuggestion;
                            }) ?>

                            <?php if (count($suggestions) > 0) : ?>
                                <?php foreach ($suggestions as $model) : ?>
                                    <?= $this->render('_checkDomainItem', ['model' => $model]) ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="bg-warning clearfix" style="padding: 1em;">
                                    <?= Html::tag('h4', Yii::t('hipanel:domain', "We can't suggest you something similar to entered domain name.")) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
