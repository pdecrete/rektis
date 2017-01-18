<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LeaveBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Leave Balances');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-balance-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Leave Balance'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'employee',
            [
                'attribute' => 'employee',
                'value' => 'employee0.fullname',
                'filter' => \app\models\Employee::find()->select(["CONCAT(surname, ' ', name) as fname", 'id'])->orderBy('fname')->indexBy('id')->column()
            ],            
            //'leave_type',
            [
                'attribute' => 'leave_type',
                'value' => 'leaveType.name',
                'filter' => \app\models\LeaveType::find()->select(['name', 'id'])->indexBy('id')->column()
            ],           
            'year',
            'days',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
