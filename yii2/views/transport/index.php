<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Transports');
$subtitle = Yii::t('app', 'Not deleted transports');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="transport-index">

    <h1><?= Html::encode($this->title) ?> <small><?= Html::encode($subtitle) ?></small></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Transport'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute' => 'employee',
                'value' => 'employee0.fullname',
                //'filter' => \app\models\Employee::find()->select(["CONCAT(surname, ' ', name) as fname", 'id'])->orderBy('fname')->indexBy('id')->column()
                'filter' => \app\models\Employee::find()->innerJoin('admapp_specialisation', 'admapp_specialisation.id=admapp_employee.specialisation')->select(["CONCAT(admapp_employee.surname, \" \", admapp_employee.name, \" του \", admapp_employee.fathersname,  \" (\", admapp_specialisation.code, \")\") as fname", "admapp_employee.id"])->orderBy("fname")->indexBy("id")->column()
            ],
            [
                'attribute' => 'mode',
                'value' => 'mode0.name',
                'filter' => \app\models\TransportMode::find()->select(['name', 'id'])->indexBy('id')->column()
            ],
            // 'type',
            'start_date:date',
            'end_date:date',
            'decision_protocol',
            'decision_protocol_date:date',
            // 'application_protocol',
            // 'application_protocol_date',
            // 'application_date',
            // 'accompanying_document',
            // 'reason',
            // 'from_to',
            // 'base',
            // 'days_applied',
            // 'klm',
            // 'ticket_value',
            // 'klm_reimb',
            // 'days_out',
            // 'day_reimb',
            // 'night_reimb',
            // 'reimbursement',
            // 'mtpy',
            // 'pay_amount',
            // 'expense_details',
            // 'funds1',
            // 'funds2',
            // 'funds3',
            // 'code719',
            // 'code721',
            // 'code722',
            // 'count_flag',
            // 'comment:ntext',
            // 'create_ts',
            // 'update_ts',
            // 'deleted',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{printButton}{delete}',
                'buttons' => [
                    'printButton' => function ($url, $model, $key) {
                        return Html::a(yii\bootstrap\Html::icon('copy'), ['copy', 'id' => $model->id], ['title' => 'Νέα μετακίνημη με αντιγραφή']);
                    }
                ],
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return $model->locked === 1 ? false : true;
                    },
                    'delete' => function ($model, $key, $index) {
                        return $model->locked === 1 ? false : true;
                    }
                ],
            ]
        ],
    ]);

    ?>
</div>
