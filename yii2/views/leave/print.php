<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
use \PhpOffice\PhpWord\TemplateProcessor;

/* @var $this yii\web\View */
/* @var $model app\models\Leave */

$this->title = $model->information;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leaves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->information, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Print');
?>
<?php
if ($model->deleted) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger',
        ],
        'body' => Yii::t('app', 'This leave is marked as deleted.'),
    ]);
}
?>
<div class="leave-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Return to view'), ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
    <?php
    $templateProcessor = new TemplateProcessor(Yii::getAlias('@vendor/admapp/resources/ADEIA_TEST_FILE.docx'));
    $templateProcessor->setValue('DATE', date('d/m/Y'));
    $templateProcessor->setValue('PROTOCOL', $model->decision_protocol);
    $templateProcessor->setValue('FULLNAME', $model->employeeObj->fullname);
    $dts = date('YmdHis');
    $templateProcessor->saveAs(Yii::getAlias("@vendor/admapp/exports/ADEIA_TEST_FILE_{$dts}.docx"));
    ?>
    <p><strong><?= $dts ?>, Saved the result document...</strong></p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
//            'employee',
            [
                'label' => $model->getAttributeLabel('employee'),
                'value' => $model->employeeObj ? $model->employeeObj->fullname : null
            ],
//            'type',
            [
                'label' => $model->getAttributeLabel('type'),
                'value' => $model->typeObj ? $model->typeObj->name : null
            ],
            'decision_protocol',
            'decision_protocol_date:date',
            'application_protocol',
            'application_protocol_date:date',
            'application_date:date',
            'accompanying_document',
            'duration',
//            'start_date:date',
            [
                'label' => $model->getAttributeLabel('start_date'),
                'value' => Yii::$app->formatter->asDate($model->start_date, 'long')
            ],
//            'end_date:date',
            [
                'label' => $model->getAttributeLabel('end_date'),
                'value' => Yii::$app->formatter->asDate($model->end_date, 'long')
            ],
            'reason',
            'comment:ntext',
            'deleted:boolean',
            'create_ts',
            'update_ts',
        ],
    ])
    ?>

</div>
