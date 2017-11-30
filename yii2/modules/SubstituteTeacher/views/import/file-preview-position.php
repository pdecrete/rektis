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
    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Validate data'), [Yii::$app->controller->id . '/' . Yii::$app->controller->action->id, 'file_id' => $file_id, 'sheet' => $sheet, 'action' => 'validate'], ['class' => 'btn btn-success']) ?>
        <?php // Html::a(Yii::t('substituteteacher', 'Import data'), [Yii::$app->controller->id . '/' . Yii::$app->controller->action->id, 'file_id' => $file_id, 'sheet' => $sheet, 'action' => 'import'], ['class' => 'btn btn-primary', 'data-confirm' => Yii::t('substituteteacher', 'Clear all data and import? Are you certain?')]) ?>
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

    <div class="table-responsive">
        <table class="table table-hover">
            <caption>Προεπισκόπηση στοιχείων. Εμφανίζονται οι πρώτες <?= $line_limit ?> γραμμές του φύλλου εργασίας. Συνολικά υπάρχουν <?= $highestRow ?> γραμμές στο φύλλο εργασίας.</caption>
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <?php
                    $clbl = 'A';
                    for ($i = 1; $i <= $highestColumnIndex; $i++) :

                        ?><th><?php echo $clbl++; ?></th><?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($worksheet->getRowIterator() as $row) : ?>
                    <?php $row_index = $row->getRowIndex(); ?>
                    <tr>
                        <th><?php echo $row_index; ?> </th>
                        <?php
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);
                        foreach ($cellIterator as $cell) :
                            $column_ltr = $cell->getColumn();
                            $col = PHPExcel_Cell::columnIndexFromString($column_ltr);
                            $row = $cell->getRow();

                            $cell_value = $cell->getValue();
                            $calc_value = BaseImportModel::getCalculatedValue($cell);
                            $cell_is_set = (!is_null($cell_value) && trim($calc_value) != '');

                            $hint_class = '';
                            if (!$cell_is_set) {
                                $hint_class = 'warning';
                            }

                            ?>
                            <td class="<?php echo $hint_class; ?>">
                                <?php echo $calc_value, ((BaseImportModel::isFormula($cell_value)) ? " <span class=\"text-info\">formula</span>" : ""); ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php if ($row_index >= $line_limit) break; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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
            <?= Html::dropDownList('operation', null, \app\modules\SubstituteTeacher\models\Operation::selectables()) ?>
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
