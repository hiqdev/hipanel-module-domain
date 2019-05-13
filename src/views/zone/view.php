<?php

use hipanel\modules\domain\grid\ZoneGridView;
use hipanel\modules\domain\menus\ZoneDetailMenu;
use hipanel\widgets\Box;
use hipanel\widgets\MainDetails;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \hipanel\modules\domain\models\Zone $model
 * @var array $models
 */

$this->title = Html::encode($model->name);
$this->params['subtitle'] = Yii::t('hipanel:domain', 'Zone detailed information') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:domain', 'Zones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .profile-block {
        text-align: center;
    }
');
?>

<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
                <?= MainDetails::widget([
                    'title' => $this->title,
                    'icon' => 'fa-shopping-basket',
                    'menu' => ZoneDetailMenu::widget(['model' => $model], ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>']),
                ]) ?>
            </div>
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel', 'Details'));
                $box->endHeader();
                $box->beginBody();
                echo ZoneGridView::detailView([
                    'model' => $model,
                    'boxed' => false,
                    'columns' => [
                        'registry',
                        'name',
                        'has_contacts',
                        'password_required',
                        'state',
                        'no',
                        'add_grace_period',
                        'add_grace_limit',
                        'autorenew_grace_period',
                        'redemption_grace_period',
                    ],
                ]);
                $box->endBody();
                $box->end();
                ?>
            </div>

        </div>
    </div>
</div>
