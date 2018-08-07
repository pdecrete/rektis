<?php

use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use app\modules\SubstituteTeacher\models\BaseImportModel;

$this->title = Yii::t('substituteteacher', 'Import information');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Positions'), 'url' => ['position/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="file-preview">

    <h1><?= Html::encode(pathinfo($model->filename, PATHINFO_BASENAME)) ?></h1>
    <h1><small>Οι επιλογές παρακάτω αφορούν στοιχεία <strong>λειτουργικών κενών</strong></small></h1>
    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Validate data'), [Yii::$app->controller->id . '/' . Yii::$app->controller->action->id, 'file_id' => $file_id, 'sheet' => $sheet, 'action' => 'validate'], ['class' => 'btn btn-success']) ?>
        <?=
        Html::button(Yii::t('substituteteacher', 'Import data'), [
            'class' => 'btn btn-primary',
            'data' => [
                'toggle' => 'modal',
                'target' => '#choose-operation-modal',
            ],
        ])

        ?>
    </p>

    <?= $this->render('_data_table', ['worksheet' => $worksheet, 'line_limit' => $line_limit, 'startRow' => 1, 'highestRow' => $highestRow, 'highestColumnIndex' => $highestColumnIndex]) ?>
</div>

<?php
Modal::begin([
    'id' => 'choose-operation-modal',
    'header' => '<h3>' . Yii::t('substituteteacher', 'Select operation for import') . '</h3>',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);

$form = ActiveForm::begin([
        'id' => 'operation-choose-form',
        'method' => 'GET',
        'action' => [
            'position',
            'file_id' => $file_id,
            'sheet' => $sheet
        ],
        'options' => ['class' => 'form-horizontal'],
        'enableClientValidation' => false,
    ]);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <?= Yii::t('substituteteacher', 'Operations') ?>
        </div>
        <div class="col-sm-8">
            <?= Html::dropDownList('operation', null, \app\modules\SubstituteTeacher\models\Operation::defaultSelectables(), ['class' => 'form-control']) ?>
            <?= Html::hiddenInput('action', 'import') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <p class="text-warning"><?= Yii::t('substituteteacher', 'After selecting the operation, all existing data linked to it will be cleared before import.') ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-right">
            <div class="form-group">
                <?=
                Html::button(Yii::t('substituteteacher', 'Cancel'), [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'toggle' => 'modal',
                        'target' => '#choose-operation-modal',
                    ],
                ])

                ?>
                <?=
                Html::submitButton(Yii::t('substituteteacher', 'Import'), [
                    'class' => 'btn btn-primary',
                    'data-confirm' => Yii::t('substituteteacher', 'Clear all data and import? Are you certain?'),
                ])

                ?>
            </div>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
Modal::end();
