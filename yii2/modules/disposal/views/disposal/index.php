<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
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

$actions = ($archived) ? '{view}': '{view} {update} {delete}';   
    
$checkboxColumn = [[ 'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model) {return ['value' => $model['disposal_id']];}
                  ]];
$columns = [[   'attribute' => 'teacher_surname',
                'label' => DisposalModule::t('modules/disposal/app', 'Surname'),
            ],
            [   'attribute' => 'teacher_name',
                'label' => DisposalModule::t('modules/disposal/app', 'Name'),
            ],
            [   'attribute' => 'teacher_registrynumber',
                'label' => DisposalModule::t('modules/disposal/app', 'RegNum'),
            ],
            [   'attribute' => 'code',
                'label' => DisposalModule::t('modules/disposal/app', 'Specialisation'),
            ],
            [   'attribute' => 'organic_school',
                'label' => DisposalModule::t('modules/disposal/app', 'Organic Post'),
            ],
            [   'attribute' => 'disposal_school',
                'label' => DisposalModule::t('modules/disposal/app', 'Disposal School'),
            ],
            [   'attribute' => 'disposal_startdate',
                'label' => DisposalModule::t('modules/disposal/app', 'From'),
                'format' => ['date', 'php:d/m/Y'],
                'filter' => DateControl::widget([  'model' => $searchModel,
                    'attribute' => 'disposal_startdate',
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => ['layout' => '{remove}{input}'],
                ]),
                'headerOptions' => ['class'=> 'text-center'],
            ],
            [   'attribute' => 'disposal_enddate',
                'label' => DisposalModule::t('modules/disposal/app', 'To'),
                'format' => ['date', 'php:d/m/Y'],
                'filter' => DateControl::widget([  'model' => $searchModel,
                    'attribute' => 'disposal_enddate',
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => ['layout' => '{remove}{input}'],
                ]),
                'headerOptions' => ['class'=> 'text-center'],
            ],
            [   'attribute' => 'disposal_hours',
                'label' => DisposalModule::t('modules/disposal/app', 'Hours'),
                'value' => function($model){if($model['disposal_hours'] == -1) return 'Ολική Διάθεση'; else return $model['disposal_hours'];},
                'filter' => Select2::widget([  'model' => $searchModel,
                    'attribute' => 'disposal_hours',
                    'data' => ArrayHelper::map(Disposal::getHourOptions(), 'hours', 'hours_name'),
                    'options' => ['placeholder' => '   '],
                    'pluginOptions' => ['allowClear' => true, 'width' => '80']])
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => DisposalModule::t('modules/disposal/app', 'Actions'),
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
        <p class="text-right">
        	<?= Html::a(DisposalModule::t('modules/disposal/app', 'Create Disposals\' Approval'), ['disposal-approval/create'], ['class' => 'btn btn-success', 'data-method' => 'POST']) ?>
            <?= Html::a(DisposalModule::t('modules/disposal/app', 'Create Teacher Disposal'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php else:?>
    	<p class="text-right">
    		<?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposals for Approval'), ['index'], ['class' => 'btn btn-primary']) ?>
    	</p>
    <?php endif;?>
<?php Pjax::begin(); ?>    
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
            
    ]); ?>
<?php Pjax::end(); ?>
<?= Html::endForm();?>
</div>