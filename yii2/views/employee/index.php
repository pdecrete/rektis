<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'status',
            'name',
            'surname',
            'fathersname',
            // 'mothersname',
            // 'tax_identification_number',
            // 'email:email',
            // 'telephone',
            // 'address',
            // 'identity_number',
            // 'social_security_number',
            // 'specialisation',
            // 'identification_number',
            // 'appointment_fek',
            // 'appointment_date',
            // 'service_organic',
            // 'service_serve',
            // 'position',
            // 'rank',
            // 'rank_date',
            // 'pay_scale',
            // 'pay_scale_date',
            // 'service_adoption',
            // 'service_adoption_date',
            // 'master_degree',
            // 'doctorate_degree',
            // 'work_experience',
            // 'comments:ntext',
            // 'create_ts',
            // 'update_ts',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
