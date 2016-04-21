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

            [
              'label' => 'Κατάσταση',
              'value' => 'status0.name',
              //'contentOptions' => ['style'=>'width: 10px']
            ],
            'identification_number',
            'tax_identification_number',
            [
              'label' => 'Όνομα',
              'format' => 'raw',
              'value' => function($data) { return yii\helpers\Html::a($data['surname'],['employee/view', 'id'=>$data['id']]); }
            ],
            [
              'label' => 'Επώνυμο',
              'format' => 'raw',
              'value' => function($data) { return yii\helpers\Html::a($data['surname'],['employee/view', 'id'=>$data['id']]); }
            ],
            // 'fathersname',
            // 'mothersname',
            // 'email:email',
            // 'telephone',
            // 'address',
            // 'identity_number',
            // 'social_security_number',
            [
              'label' => 'Ειδικότητα',
              'value' => 'specialisation0.code'
            ],

            // 'appointment_fek',
            // 'appointment_date',
            [
              'label' => 'Οργανική',
              'value' => 'serviceOrganic.name'
            ],
            [
              'label' => 'Υπηρέτηση',
              'value' => 'serviceServe.name'
            ],
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
