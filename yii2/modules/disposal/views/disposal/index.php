<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\modules\disposal\DisposalModule;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use app\modules\disposal\models\Disposal;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\disposal\models\DisposalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->title = ($archived) ? DisposalModule::t('modules/disposal/app', 'Processed Disposals') : DisposalModule::t('modules/disposal/app', 'Disposals for Approval');
$this->params['breadcrumbs'][] = $this->title;
//echo "<pre>"; print_r($dataProvider->models); echo "</pre>"; die();

$actions = ($archived && !$republish) ? '{view}': '{view} {update} {delete}';   
    
$checkboxColumn = [[ 'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model) {return ['value' => $model['disposal_id']];}
                  ]];
$columns = [[   'attribute' => 'teacher_surname',
                'label' => DisposalModule::t('modules/disposal/app', 'Full name'),
                'options' => ['width' => '65'],
                'value' => function ($model) {return $model['teacher_surname'] . ' ' . $model['teacher_name'];}
            ],
/*             [   'attribute' => 'teacher_name',
                'label' => DisposalModule::t('modules/disposal/app', 'Name'),
            ], */
            [   'attribute' => 'code',
                'label' => DisposalModule::t('modules/disposal/app', 'Specialisation'),
                'filter' => Select2::widget([  'model' => $searchModel,
                    'attribute' => 'code',
                    'data' => ArrayHelper::map($specialisations, 'code', 'code'),
                    'options' => ['placeholder' => '   '],
                    'pluginOptions' => ['allowClear' => true, 'width' => '60']
                ])
            ],
            [   'attribute' => 'directorate_shortname',
                'label' => DisposalModule::t('modules/disposal/app', 'Directorate'),
                'filter' => Select2::widget([  'model' => $searchModel,
                    'attribute' => 'directorate_shortname',
                    'data' => ArrayHelper::map($directorates, 'directorate_shortname', 'directorate_shortname'),
                    'options' => ['placeholder' => '   '],
                    'pluginOptions' => ['allowClear' => true, 'width' => '65']
                ])
            ],
            [   'attribute' => 'localdirdecision_protocol',
                'label' => DisposalModule::t('modules/disposal/app', 'Directorate Protocol'),
                'filter' => Select2::widget([  'model' => $searchModel,
                    'attribute' => 'localdirdecision_protocol',
                    'data' => ArrayHelper::map($decisions_protocols, 'localdirdecision_protocol', 'localdirdecision_protocol'),
                    'options' => ['placeholder' => '   '],
                    'pluginOptions' => ['allowClear' => true, 'width' => '80']
                ])
            ],
            [   'attribute' => 'organic_school',
                'label' => DisposalModule::t('modules/disposal/app', 'Organic Post'),
                'options' => ['width' => '65']
            ],
            [   'attribute' => 'disposal_school',
                'label' => DisposalModule::t('modules/disposal/app', 'Disposal'),
                'options' => ['width' => '65']
            ],
            [   'attribute' => 'disposalreason_description',
                'label' => DisposalModule::t('modules/disposal/app', 'Reason'),
                'filter' => Select2::widget([  'model' => $searchModel,
                    'attribute' => 'disposalreason_description',
                    'data' => ArrayHelper::map($disposal_reasons, 'disposalreason_description', 'disposalreason_description'),
                    'options' => ['placeholder' => '   '],
                    'pluginOptions' => ['allowClear' => true, 'width' => '80']
                ])
            ],
            [   'attribute' => 'disposal_startdate',
                'label' => DisposalModule::t('modules/disposal/app', 'From'),
                
                'format' => ['date', 'php:d-m-Y'],
                'filter' => DateControl::widget([  'model' => $searchModel,
                    'attribute' => 'disposal_startdate',
                    'ajaxConversion'=>false,
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => ['layout' => '{remove}{input}'],
                ]),
                'headerOptions' => ['class'=> 'text-center'],
            ],
            [   'attribute' => 'disposal_enddate',
                'label' => DisposalModule::t('modules/disposal/app', 'To'),
                'format' => ['date', 'php:d-m-Y'],
                'filter' => DateControl::widget([  'model' => $searchModel,
                    'attribute' => 'disposal_enddate',
                    'ajaxConversion'=>false,
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => ['layout' => '{remove}{input}'],
                ]),
                'headerOptions' => ['class'=> 'text-center'],
            ],
            [   'attribute' => 'disposal_hours',
                'label' => DisposalModule::t('modules/disposal/app', 'Hours'),
                'value' => function($model){if($model['disposal_hours'] == Disposal::FULL_DISPOSAL) return 'Ολική Διάθεση'; else return $model['disposal_hours'];},
                'filter' => Select2::widget([  'model' => $searchModel,
                    'attribute' => 'disposal_hours',
                    'data' => ArrayHelper::map(Disposal::getHourOptions(), 'hours', 'hours_name'),
                    'options' => ['placeholder' => '   '],
                    'pluginOptions' => ['allowClear' => true, 'width' => '60']
                    ])
            ],
            ['class' => 'yii\grid\ActionColumn',
                //'header' => DisposalModule::t('modules/disposal/app', 'Actions'),
                'contentOptions' => ['class' => 'text-nowrap'],
                'template' => $actions,
                'urlCreator' => function ($action, $model) use ($archived) {
                if ($action === 'view') {
                    return Url::to(['/disposal/disposal/view', 'id' =>$model['disposal_id'], 'archived' => $archived]);
                }
                if ($action === 'update') {
                    return Url::to(['/disposal/disposal/update', 'id' =>$model['disposal_id']]);
                }
                if ($action === 'delete') {
                    return Url::to(['/disposal/disposal/delete', 'id' =>$model['disposal_id']]);
                }
                },
                'headerOptions' => ['class'=> 'text-center']
            ],
        ];
    if(!$archived) 
        $columns = array_merge($checkboxColumn, $columns);
?>
<div class="disposal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?=Html::beginForm(['archiveform'], 'post');?>
	<?php if(!$archived):?>
    	<div class="text-right">
        	<?= Html::a(DisposalModule::t('modules/disposal/app', 'Import Disposals from Excel'), ['importdisposals'], ['class' => 'btn btn-primary']) ?>
        	<?= Html::a(DisposalModule::t('modules/disposal/app', 'Delete'), ['massdelete'], ['data'=>['confirm'=>DisposalModule::t('modules/disposal/app', "Are you sure you want to delete these items?")], 'class' => 'btn btn-danger', 'data-method' => 'POST']) ?>
            <div class="btn-group">
          		<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
          			<?= DisposalModule::t('modules/disposal/app', 'Create'); ?> <span class="caret"></span>
      			</button>
      			<ul class="dropdown-menu" role="menu">
      				<li><?= Html::a(DisposalModule::t('modules/disposal/app', 'Approval'), ['disposal-approval/create'], ['data-method' => 'POST']) ?></li>
            		<li><?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposal'), ['create']) ?></li>
      			</ul>
        	</div>
    	</div><br />
    <?php else:?>
    	<p class="text-right">
    		<?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposals for Approval'), ['index'], ['class' => 'btn btn-primary']) ?>
    	</p>
    <?php endif;?>

	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
            
    ]); ?>
    
<?= Html::endForm();?>
</div>