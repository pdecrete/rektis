<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Εργαζόμενοι';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div id='searchForm' style="display: none;">
      <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
   <?php
      $searchJs = "$('#searchBtn').click(function(){ \$('#searchForm').toggle('slow'); });";
      $this->registerJs($searchJs, $this::POS_END);
   ?>
    <p>
        <?= Html::a('Αναζήτηση', NULL, ['id' => 'searchBtn', 'class' => 'btn btn-info']) ?>
        <?= Html::a('Προσθήκη εργαζομένου', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
              'attribute' => 'status',
              'label' => 'Κατάσταση',
              'value' => 'status0.name',
              'filter' => \app\models\EmployeeStatus::find()->select(['name', 'name'])->indexBy('name')->column(),
              //'filter' => \kartik\select2\Select2::widget(['name'=>'status1','data'=> \app\models\EmployeeStatus::find()->select(['name', 'name'])->indexBy('name')->column(),'options' =>['width' => '100%']]),
              'contentOptions' => ['style'=>'width: 5%']
            ],
            [
              'attribute' => 'identification_number',
              'label' => 'Α.Μ.',
              'contentOptions' => ['style'=>'width: 5%']
            ],
            [
              'attribute' => 'tax_identification_number',
              'label' => 'Α.Φ.Μ.',
              'contentOptions' => ['style'=>'width: 5%']
            ],
            [
              'attribute' => 'name',
              'label' => 'Όνομα',
              'format' => 'raw',
              'value' => function($data) { return yii\helpers\Html::a($data['name'],['employee/view', 'id'=>$data['id']]); }
            ],
            [
              'attribute' => 'surname',
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
              'attribute' => 'specialisation',
              'label' => 'Ειδικότητα',
              'value' => 'specialisation0.code',
              'contentOptions' => ['style'=>'width: 5%']
            ],

            // 'appointment_fek',
            // 'appointment_date',
            [
              'attribute' => 'service_organic',
              'label' => 'Οργανική',
              'value' => 'serviceOrganic.name'
            ],
            [
              'attribute' => 'service_serve',
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
