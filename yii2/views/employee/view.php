<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $model->surname . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">

     <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete?'),
                'method' => 'post',
            ],
        ])
        ?>
		<?= Html::a(Yii::t('app', 'Create Leave'), [ 'leave/create', 'employee' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#personal" aria-controls="personal" role="tab" data-toggle="tab"><?= Yii::t('app', 'Personal') ?></a></li>
        <li role="presentation"><a href="#service" aria-controls="service" role="tab" data-toggle="tab"><?= Yii::t('app', 'Professional') ?></a></li>
        <li role="presentation"><a href="#leaves" aria-controls="leaves" role="tab" data-toggle="tab"><?= Yii::t('app', 'Leaves') ?></a></li>
        <li role="presentation"><a href="#system" aria-controls="system" role="tab" data-toggle="tab"><?= Yii::t('app', 'System information') ?></a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade-in active" id="personal">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'surname',
                    'fathersname',
                    'mothersname',
                    'tax_identification_number',
                    'email:email',
                    'telephone',
                    'mobile',
                    'address',
                    'identity_number',
                    'social_security_number',
                ]
            ])
            ?>
        </div>
        <div role="tabpanel" class="tab-pane fade-in" id="service">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => Yii::t('app', 'Status'),
                        'attribute' => 'status0.name',
                    ],
                    [
                        'label' => Yii::t('app', 'Specialisation'),
                        'attribute' => 'specialisation0.code',
                    ],
                    'identification_number',
                    'appointment_fek',
                    [
                        'label' => Yii::t('app', 'Appointment Date'),
                        'attribute' => function ($data) {
                            return \Yii::$app->formatter->asDate($data['appointment_date']);
                        }
                    ],
                    [
                        'label' => Yii::t('app', 'Service Organic'),
                        'attribute' => 'serviceOrganic.name'
                    ],
                    [
                        'label' => Yii::t('app', 'Service Serve'),
                        'attribute' => 'serviceServe.name'
                    ],
                    [
                        'label' => Yii::t('app', 'Position'),
                        'attribute' => 'position0.name'
                    ],
                    [
                        'label' => Yii::t('app', 'Rank'),
                        'attribute' => 'rank0'
                    ],
                    //'rank0',
                    [
                        'label' => Yii::t('app', 'Rank Date'),
                        'attribute' => function ($data) {
                            return \Yii::$app->formatter->asDate($data['rank_date']);
                        }
                    ],
                    'pay_scale',
                    [
                        'label' => Yii::t('app', 'Pay Scale Date'),
                        'attribute' => function ($data) {
                            return \Yii::$app->formatter->asDate($data['pay_scale_date']);
                        }
                    ],
                    'service_adoption',
                    [
                        'label' => Yii::t('app', 'Service Adoption Date'),
                        'attribute' => function ($data) {
                            return \Yii::$app->formatter->asDate($data['service_adoption_date']);
                        }
                    ],
                    'master_degree',
                    'doctorate_degree',
                    'work_experience',
                ],
            ])
            ?>
        </div>
        <div role="tabpanel" class="tab-pane fade-in" id="leaves">
        <h1>Σύνολα αδειών <small> Οι διεγραμμένες άδειες δεν λαμβάνονται υπόψη στον υπολογισμό.</small></h1>
		<?php					
			//Για τις διαγραμμένες χρησιμοποιώ flag $model->deleted (default 0 on Model->Create)
			$count = $model->getCountLeavesTotals();
			$leavesSumDataProvider = $model->getLeavesTotals();
			$leavesSumDataProvider->totalCount = $count;
			$leavesSumDataProvider->pagination = [
					'pagesize' => 5, 
					'pageParam' => 'sumPage',
					];
			$leavesSumDataProvider->sort =[
					'attributes' => [
						'leaveYear' => SORT_DESC,
						'leaveTypeName' => SORT_ASC,
						],
					'sortParam' => 'sumSort',
				];
				
		?>
		<?php Pjax::begin(); ?>
		<?=
			GridView::widget([
				'dataProvider' => $leavesSumDataProvider,       
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					['label' => Yii::t('app', 'Leave type'),
						'attribute' => 'leaveTypeName'],
					['label' => Yii::t('app', 'Year'),
						'attribute' => 'leaveYear'],
					['label' => Yii::t('app', 'Duration in days'),
						'attribute' => 'duration'],			
				],	
			]);
		?>
		<?php Pjax::end(); ?>
	
			<?php
//            $sd = (new \yii\db\Query())
//                    ->from('admapp_leave')
//                    ->where(['employee' => 1, 'deleted' => 0])
//                    ->sum('duration');
//            yii\helpers\VarDumper::dump($sd);
//            \yii\helpers\VarDumper::dump($model->leavesDuration);

            $leavesDataProvider = new ArrayDataProvider([
                'allModels' => $model->leaves,
                'pagination' => [
                    'pagesize' => 10,
                    'pageParam' => 'leavePage',
                ],
                'sort' => [
                    'attributes' => [
                        'duration' => [
                            'asc' => ['start_date' => SORT_ASC, 'duration' => SORT_ASC],
                            'desc' => ['start_date' => SORT_DESC, 'duration' => SORT_DESC],
                            'default' => SORT_DESC,
                            'label' => 'Duration',
                        ],
                        'decision_protocol_date',
                        'type',
                    ],
                    'sortParam' => 'leaveSort',	
                ]
            ]);
            echo $this->render('/leave/_employee', ['dataProvider' => $leavesDataProvider, 'employeeModel' => $model]);
            ?>
        </div>        
        <div role="tabpanel" class="tab-pane fade-in" id="system">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'comments:ntext',
                    [
                        'label' => Yii::t('app', 'Created At'),
                        'attribute' => function ($data) {
                            return \Yii::$app->formatter->asDateTime($data['create_ts']);
                        }
                    ],
                    [
                        'label' => Yii::t('app', 'Updated At'),
                        'attribute' => function ($data) {
                            return \Yii::$app->formatter->asDateTime($data['update_ts']);
                        }
                    ]
                ],
            ])
            ?>
        </div>
    </div>
    <p>&nbsp;</p>
</div>
