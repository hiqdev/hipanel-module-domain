<?php

use hipanel\modules\domain\grid\HostGridView;
use hipanel\modules\domain\menus\HostDetailMenu;
use hipanel\widgets\Box;
use hipanel\widgets\ClientSellerLink;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

$this->title = Html::encode($model->host);
$this->params['subtitle'] = Yii::t('hipanel:domain', 'Name server detailed information') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:domain', 'Name Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin() ?>
    <div class="row">
        <div class="col-md-3">
            <?php Box::begin([
                'options' => [
                    'class' => 'box-solid',
                ],
                'bodyOptions' => [
                    'class' => 'no-padding',
                ],
            ]) ?>
                <div class="profile-user-img text-center">
                    <i class="fa fa-globe" style="font-size:7em"></i>
                </div>
                <p class="text-center">
                    <span class="profile-user-role"><?= $this->title ?></span>
                    <br>
                    <span class="profile-user-name"><?= ClientSellerLink::widget(['model' => $model]) ?></span>
                </p>
                <div class="profile-usermenu">
                    <?= HostDetailMenu::widget(['model' => $model]) ?>
                </div>
            <?php Box::end() ?>
        </div>

        <div class="col-md-5">
            <?= HostGridView::detailView([
                'model'   => $model,
                'columns' => [
                    'seller_id', 'client_id',
                    'domain', 'bold_host', 'ips',
                ],
            ]) ?>
        </div>
    </div>
<?php Pjax::end() ?>
