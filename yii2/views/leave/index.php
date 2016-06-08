<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LeaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Leaves');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Leave'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            [
                'attribute' => 'employee',
                'value' => 'employeeObj.fullname',
                'filter' => \app\models\Employee::find()->select(["CONCAT(name, ' ', surname)", 'id'])->indexBy('id')->column()
            ],
            [
                'attribute' => 'type',
                'value' => 'typeObj.name',
                'filter' => \app\models\LeaveType::find()->select(['name', 'id'])->indexBy('id')->column()
            ],
            'duration',
            'start_date:date',
//            [
//                'attribute' => 'start_date',
//                'value' => function ($model) {
//                    return \Yii::$app->formatter->asDate($model->start_date);
//                },
//            ],
            'end_date:date',
            'decision_protocol',
            'decision_protocol_date:date',
            // 'application_protocol',
            // 'application_protocol_date',
            // 'application_date',
            // 'accompanying_document',
            // 'reason',
            // 'comment:ntext',
            // 'create_ts',
            // 'update_ts',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?></div>
