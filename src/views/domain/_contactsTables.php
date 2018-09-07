<?php

use hipanel\modules\client\grid\ContactGridView;
use yii\helpers\Html;
$this->registerCss("
#domain-contacts-list {
    margin-bottom: 0px;
}
#domain-contacts-list td {
    padding: 0;
}
");
?>

<?php foreach ($model->contactTypesWithLabels() as $contactType => $label) : ?>
    <?php $contact = $model->{$contactType} ?>
    <div class="box-comment">
        <div class="comment-text" style="margin-left: 10px;">
            <span class="username">
                <?= mb_strtoupper($label) ?>
                <?= Html::a(Yii::t('hipanel', 'detailed information'), ['@contact/view', 'id' => $contact->id], ['class' => 'pull-right']) ?>
            </span>
            <table id="domain-contacts-list" class="table" style="table-layout: fixed">
                <tr>
                    <td>
                        <?= $contact->voice_phone ?>
                    </td>
                    <td>
                        <?= $contact->name ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $contact->email ?>
                    </td>
                    <td>
                        <?= $contact->renderAddress() ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php endforeach ?>
