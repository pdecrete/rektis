<?php

use app\modules\schooltransport\Module;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\schooltransport\models\SchtransportTransportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->title = Module::t('modules/schooltransport/app', 'School Transportations');
$this->params['breadcrumbs'][] = $this->title;

//echo "<pre>"; print_r($programcategs); echo "</pre>";die();

?>
<div class="schtransport-transport-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p class="text-right">
    	<button type="button" class="btn btn-success" data-toggle="collapse" data-target="#programsCategs">
        	<?php echo Module::t('modules/schooltransport/app',  'Create Transportation'); ?>
        </button>
    </p>
    
    <div id="programsCategs" class="collapse">
        <div class="container-fluid well">
      		<div class="row">
				<?php 
				    foreach ($programcategs as $key=>$programcateg){
				        $sep = ($programcateg['PROGRAMCATEG_ID'] == 3);
				        echo "<ul>";
				        if(count($programcateg['SUBCATEGS']) == 0)
				            echo "<li><strong><a href=" . Url::to(['create', 'id' => $programcateg['PROGRAMCATEG_ID'], 'sep' => $sep]) . ">{$programcateg['TITLE']}</a></strong></li>";
				        else {
				            echo "<li><strong>{$programcateg['TITLE']}</strong></li>";
				            echo "<ul>";
				            foreach ($programcateg['SUBCATEGS'] as $subcateg)
				                echo "<li><a href=" . Url::to(['create', 'id' => $subcateg['programcategory_id'], 'sep' => $sep]) . ">" . $subcateg['programcategory_programtitle']  . "</a></li>";
			                echo "</ul>";				                
				        }
				        echo "</ul>";
				    }
				?>
    		</div>
    	</div>
    </div>
    <!--  <p class="pull-right">
        <?= Html::a(Module::t('modules/schooltransport/app',  'Create Transportation'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->
<?php Pjax::begin(); ?>    
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],              
            ['attribute' => 'school_name',
             'label' => Module::t('modules/schooltransport/app', 'School Unit'), 
            ],
            ['attribute' => 'meeting_country',
             'label' => Module::t('modules/schooltransport/app', 'Transportation Country')],
            ['attribute' => 'meeting_city',
             'label' => Module::t('modules/schooltransport/app', 'Transportation City')
            ],
            ['attribute' => 'transport_startdate',
             'label' => Module::t('modules/schooltransport/app', 'Transportation Start'),
             'format' => ['date', 'php:d-m-Y'],
             'filter' => DateControl::widget([  'model' => $searchModel,
                                                'attribute' => 'transport_startdate',
                                                'type' => DateControl::FORMAT_DATE,
                                                'widgetOptions' => ['layout' => '{remove}{input}'],
                                            ])
            ],
            ['attribute' => 'transport_enddate',
             'label' => Module::t('modules/schooltransport/app', 'Transportation End'),
             'format' => ['date', 'php:d-m-Y'],
             'filter' => DateControl::widget([  'model' => $searchModel,
                                                'attribute' => 'transport_enddate',
                                                'type' => DateControl::FORMAT_DATE,
                                                'widgetOptions' => ['layout' => '{remove}{input}'],
                                            ])
            ],
            ['attribute' => 'meeting_startdate',
             'label' => Module::t('modules/schooltransport/app', 'Meeting Start'),
             'format' => ['date', 'php:d-m-Y'],
             'filter' => DateControl::widget([  'model' => $searchModel,
                                                'attribute' => 'meeting_startdate',
                                                'type' => DateControl::FORMAT_DATE,
                                                'widgetOptions' => ['layout' => '{remove}{input}'],
                ])
            ],
            ['attribute' => 'meeting_enddate',
             'label' => Module::t('modules/schooltransport/app', 'Meeting End'),
             'format' => ['date', 'php:d-m-Y'],
             'filter' => DateControl::widget([  'model' => $searchModel,
                                                'attribute' => 'meeting_enddate',
                                                'type' => DateControl::FORMAT_DATE,
                                                'widgetOptions' => ['layout' => '{remove}{input}'],
                ])
            ],
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view} {update} {delete} {download}',
             'buttons' => ['download' => function ($url, $model) {
                                                    return Html::a('<span class="glyphicon glyphicon-download"></span>',
                                                                    $url,
                                                                    ['title' => Module::t('modules/schooltransport/app', 'Download Decision'),
                                                                     'data-method' => 'post'
                                                                    ]);
                                              }],
             'urlCreator' => function ($action, $model) {
                if ($action === 'delete') {
                    $url = Url::to(['/schooltransport/schtransport-transport/delete', 'id' =>$model['transport_id']]);
                    return $url;
                }
                if ($action === 'update') {
                    $url = Url::to(['/schooltransport/schtransport-transport/update', 'id' =>$model['transport_id']]);
                    return $url;
                }
                if ($action === 'view') {
                    $url = Url::to(['/schooltransport/schtransport-transport/view', 'id' =>$model['transport_id']]);
                    return $url;
                }
                if ($action === 'download') {
                    $url = Url::to(['/schooltransport/schtransport-transport/download', 'id' =>$model['transport_id']]);
                    return $url;
                }
            },
             'contentOptions' => ['class' => 'text-nowrap'],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
