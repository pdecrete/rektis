<?php

use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use app\modules\SubstituteTeacher\models\BaseImportModel;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use app\modules\SubstituteTeacher\models\Specialisation;
use kartik\select2\Select2;
use yii\web\View;
use app\modules\SubstituteTeacher\models\Operation;

$this->title = Yii::t('substituteteacher', 'Import information');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Teachers'), 'url' => ['teacher/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="file-preview">

    <h1>
        <?= Html::encode(pathinfo($model->filename, PATHINFO_BASENAME)) ?>
    </h1>
    <h1>
        <small>Οι επιλογές παρακάτω αφορούν στοιχεία
            <strong>αναπληρωτών</strong>
        </small>
    </h1>
    <p>
        <?php
        $button_options = [
            'class' => 'btn btn-primary',
            'data' => [
                'toggle' => 'modal',
                'target' => '#choose-year-modal',
                'daction' => 'import',
                'dbtnlabel' => Yii::t('substituteteacher', 'Import'),
                'dbtnconfirm' => Yii::t('substituteteacher', 'Are you certain?')
            ],
        ];
        if ($hasData === false) {
            $button_options['disabled'] = 'disabled';
        }
        echo Html::button(Yii::t('substituteteacher', 'Import data'), $button_options);
        ?>
    </p>

    <div class="table-responsive">
        <table class="table table-hover">
            <?php if ($hasData === false) : ?>
            <caption>Δεν φαίνεται να υπάρχουν στοιχεία στο φύλλο εργασίας.</caption>
            <?php else: ?>
            <caption>Προεπισκόπηση στοιχείων. Εμφανίζονται οι γραμμές <?= $teachersStartRow ?> έως <?= $line_limit ?> του φύλλου εργασίας. 
                Συνολικά υπάρχουν <?= $highestRow ?> γραμμές στο φύλλο εργασίας.</caption>
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <?php
                    $clbl = 'A';
                    for ($i = 1; $i <= $highestColumnIndex; $i++) :

                        ?>
                    <th>
                        <?php echo $clbl++; ?>
                    </th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($worksheet->getRowIterator($teachersStartRow) as $row) : ?>
                <?php $row_index = $row->getRowIndex(); ?>
                <tr>
                    <th>
                        <?php echo $row_index; ?>
                    </th>
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
                <?php if ($row_index >= $line_limit) {
                                break;
                            } ?>
                <?php endforeach; ?>
            </tbody>
            <?php endif; ?>
        </table>
    </div>
</div>

<?php
Modal::begin([
    'id' => 'choose-year-modal',
    'header' => '<h3>' . Yii::t('substituteteacher', 'Select year to import teachers to') . '</h3>',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);

$form = ActiveForm::begin([
        'id' => 'year-choose-form',
        'method' => 'GET',
        'action' => [
            'registry',
            'file_id' => $file_id,
            'sheet' => $sheet
        ],
        'options' => ['class' => 'form-horizontal'],
        'enableClientValidation' => false,
    ]);
echo Html::hiddenInput('action', 'import', ['id' => 'action-input-container']);

?>
<div class="container-fluid">
    <div class="row form-group">
        <div class="col-sm-4">
            <?= Yii::t('substituteteacher', 'Years') ?>
        </div>
        <div class="col-sm-8">
            <?= Html::dropDownList('year', null, Operation::selectables('year', 'year', null, function ($aq) {
                return $aq->orderBy(['year' => SORT_DESC]);
            }), ['class' => 'form-control']) ?>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4">
            <?= Yii::t('substituteteacher', 'Teacher board') ?>
        </div>
        <div class="col-sm-8">
            <?= Html::dropDownList('board_type', null, TeacherBoard::getChoices('board_type'), ['class' => 'form-control']) ?>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-4">
            <?= Yii::t('substituteteacher', 'Specialisation') ?>
        </div>
        <div class="col-sm-8">
            <?=
                Select2::widget([
                    'name' => 'specialisation_id',
                    'data' => Specialisation::selectables(),
                    'options' => [
                        'placeholder' => Yii::t('substituteteacher', 'Choose...'),
                        'multiple' => false
                    ],
                ]);
            ?>
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
                        'target' => '#choose-year-modal',
                    ],
                ])

                ?>
                <?=
                Html::submitButton(Yii::t('substituteteacher', 'Import'), [
                    'id' => 'action-submit-btn',
                    'class' => 'btn btn-primary',
                    'data-confirm' => Yii::t('substituteteacher', 'Are you certain?'),
                ])

                ?>
            </div>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
Modal::end();

$fix_modal_select2 = '$("#choose-year-modal").removeAttr("tabindex");';
$this->registerJs($fix_modal_select2, View::POS_READY);

$modal_worker = <<< MODALACTIONS
$('#choose-year-modal').on('show.bs.modal', function (event) {
  var modal = $(this)
  var button = $(event.relatedTarget);
  var daction = button.data('daction');
  var dbtnlabel = button.data('dbtnlabel');
  var dbtnconfirm = button.data('dbtnconfirm');

  modal.find('.modal-body #action-input-container').val(daction);
  btn_container = modal.find('.modal-body #action-submit-btn');
  btn_container.text(dbtnlabel);
  btn_container.attr('data-confirm', dbtnconfirm);
  if ('validate' === daction) {
    btn_container.removeClass('btn-primary').addClass('btn-success');
  } else {
    btn_container.removeClass('btn-success').addClass('btn-primary');
  }
  console.log(daction);
  console.log(daction === 'validate');  
})
MODALACTIONS;
$this->registerJs($modal_worker, View::POS_END);
