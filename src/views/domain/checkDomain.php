<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use hipanel\modules\domain\assets\DomainCheckPluginAsset;

DomainCheckPluginAsset::register($this);
hipanel\frontend\assets\IsotopeAsset::register($this);
Yii::$app->assetManager->forceCopy = true; // todo: remove this string
//
//$this->registerJs(<<<JS
//// init Isotope
//var grid = $('.domain-list').isotope({
//    itemSelector: '.domain-line'
//});
//
//// store filter for each group
//var filters = {};
//
//$('.filters').on( 'click', '.btn', function() {
//    var _this = $(this);
//    // get group key
//    var buttonGroup = _this.parents('.list-unstyled');
//    var filterGroup = buttonGroup.attr('data-filter-group');
//    // set filter for group
//    filters[ filterGroup ] = _this.attr('data-filter');
//    // combine filters
//    var filterValue = concatValues( filters );
//    // set filter for Isotope
//    grid.isotope({ filter: filterValue });
//});
//// change is-checked class on buttons
//$('.button-group').each( function( i, buttonGroup ) {
//    var buttonGroup = $( buttonGroup );
//    buttonGroup.on( 'click', 'button', function() {
//      buttonGroup.find('.is-checked').removeClass('is-checked');
//      $( this ).addClass('is-checked');
//    });
//});
//  // flatten object by concatting values
//function concatValues( obj ) {
//  var value = '';
//  for ( var prop in obj ) {
//    value += obj[ prop ];
//  }
//  return value;
//}
//JS
//);


$this->title = Yii::t('app', 'Domain check');
$this->breadcrumbs->setItems([
    $this->title,
]);
$model->domain = empty($model->domain) ? Yii::$app->request->get('domain-check') : $model->domain;
if (!empty($results)) {
    $this->registerJs(<<<'JS'
    $('.domain-list').domainsCheck({
        domainRowClass: '.domain-line',
        success: function(data, domain, element) {
            var $elem = $(element).find("div[data-domain='" + domain + "']");
            $elem.replaceWith(data);
            return this;
        },
        finally: function() {
            console.log('finally');
        }
    });
JS
    );
}
?>

<?php if (!empty($results)) : ?>
    <div class="row">
        <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Status</h3>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#" data-filter="">All<span class="label label-default pull-right">12</span></a></li>
                        <li><a href="#" data-filter=".available">Available<span class="label label-default pull-right">12</span></a></li>
                        <li><a href="#" data-filter=".unavailable">Unavailable<span class="label label-default pull-right">12</span></a></li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Special</h3>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#" data-filter="">All<span class="label label-default pull-right">12</span></a></li>
                        <li><a href="#" data-filter=".popular">Popular Domains<span class="label label-default pull-right">12</span></a></li>
                        <li><a href="#" data-filter=".promotion">Promotion<span class="label label-default pull-right">12</span></a></li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Categories</h3>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#" data-filter="">All<span class="label label-default pull-right">12</span></a></li>
                        <li><a href="#" data-filter=".adult">Adult<span class="label label-default pull-right">12</span></a></li>
                        <li><a href="#" data-filter=".generic">Generic<span class="label label-default pull-right">12</span></a></li>
                        <li><a href="#" data-filter=".european">European<span class="label label-default pull-right">12</span></a></li>
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
                            <strong><?= Yii::t('app', 'There are no available domain zones') ?>!</strong>
                        </div>
                    <?php endif; ?>
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
                                    class="col-md-2"><?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-info btn-flat btn-lg btn-block']); ?></div>
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


            <div class="box box-solid">
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="domain-list">
                        <?php foreach ($results as $line) : ?>
                            <?= $this->render('_checkDomainLine', ['line' => $line]) ?>
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

    .domain-line:last-child {
        border-bottom: 0;
        margin-bottom: 0;
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
</style>