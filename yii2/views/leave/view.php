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
        Html::a(Yii::t('app', 'Print file'), ['print', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to print this leave?'),
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
