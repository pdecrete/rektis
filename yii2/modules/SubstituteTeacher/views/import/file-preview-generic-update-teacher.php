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

    <h1><?= Html::encode(pathinfo($model->filename, PATHINFO_BASENAME)) ?></h1>
    <h1><small>Οι επιλογές παρακάτω αφορούν στοιχεία <strong>αναπληρωτών για ενημέρωση</strong></small></h1>
    <p>
        <?=
        Html::button(Yii::t('substituteteacher', 'Update data'), [
            'class' => 'btn btn-primary',
            'data' => [
                'toggle' => 'modal',
                'target' => '#choose-year-modal',
                'daction' => 'import',
                'dbtnlabel' => Yii::t('substituteteacher', 'Update'),
                'dbtnconfirm' => Yii::t('substituteteacher', 'Update teachers information. Are you certain?')
            ],
        ])

        ?>
        <a class="btn btn-info" role="button" data-toggle="collapse" href="#headersInfo" aria-expanded="false" aria-controls="headersInfo">Αποδεκτά στοιχεία</a>
    </p>

    <div class="collapse" id="headersInfo">
        <div class="panel panel-info">
            <div class="panel-heading">
            <h3 class="panel-title">Αποδεκτά λεκτικά</h3>
            </div>
            <div class="panel-body">
                <p>Τα παρακάτω λεκτικά για τον προσδιορισμό των στοιχείων είναι αποδεκτά και μπορείτε να τα χρησιμοποιήσετε ως επικεφαλίδα.
                    Το λεκτικό <?= $key_field ?> είναι απαραίτητο να υπάρχει.</p>
                <p>
                <?php 
                    array_walk($supported_fields_wlabels, function ($v, $k) {
                        echo "&mdash; {$v} ή '{$k}'<br>"; 
                    });
                ?>
                </p>
            </div>
        </div>
    </div>

    <?= $this->render('_data_table', compact('worksheet', 'line_limit', 'highestRow', 'highestColumnIndex')) ?>
</div>

<?php
Modal::begin([
    'id' => 'choose-year-modal',
    'header' => '<h3>' . Yii::t('substituteteacher', 'Select year to update pottential yearly information of teacher') . '</h3>',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);

$form = ActiveForm::begin([
        'id' => 'year-choose-form',
        'method' => 'GET',
        'action' => [
            'generic-update-teacher',
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
    <div class="row">
        <div class="col-sm-12">
            <p class="text-warning"><?= Yii::t('substituteteacher', 'Data provided in the worksheet will update currently saved information of teachers.') ?></p>
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
                    'data-confirm' => Yii::t('substituteteacher', 'Update teachers information. Are you certain?'),
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
