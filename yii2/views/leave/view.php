<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\Leave */

$this->title = $model->information;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leaves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this leave?'),
                'method' => 'post',
            ],
        ])
        ?>
        <?=
        Html::a(Yii::t('app', 'Leave file'), ['print', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                //'confirm' => Yii::t('app', 'Are you sure you want to print this leave?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
//            'employee',
            [
                'label' => $model->getAttributeLabel('employee'),
                'value' => $model->employeeObj ? $model->employeeObj->fullname . ' ' . Html::a('<span class="glyphicon glyphicon-chevron-right"></span>', ['/employee/view', 'id' => $model->employee], ['class' => 'btn btn-primary btn-xs', 'role' => 'button']) : null,
                'format' => 'raw'
//                'value' => $model->employeeObj ? $model->employeeObj->fullname : null
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
            'extra_reason1',
            'extra_reason2',
            'extra_reason3',
            'extra_reason4',
            'extra_reason5',
            'extra_reason6',
            'extra_reason7',
            'extra_reason8',
            'extra_reason9',
            'extra_reason10',
            'deleted:boolean',
            'create_ts',
            'update_ts',
//            'emailed_at',
            [
                'label' => Yii::t('app', 'Sent at'),
                'value' => $model->leavePrints ? $model->leavePrints[0]->send_ts : null,
                'format' => 'raw',
            ],
//            'emailed_to',
            [
                'label' => Yii::t('app', 'Email recipients'),
                'value' => $model->leavePrints ? $model->leavePrints[0]->to_emails : null,
                'format' => 'raw',
            ],
        ],
    ])
    ?>

</div>
