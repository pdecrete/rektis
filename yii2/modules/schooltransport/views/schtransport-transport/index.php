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
				        echo "<ul>";
				        if(count($programcateg) == 0)
				            echo "<li><strong><a href=" . Url::to('create', $key) . ">{$programcateg['TITLE']}</a></strong></li>";
				        else {
				            echo "<li><strong>{$programcateg['TITLE']}</strong></li>";
				            echo "<ul>";
				            foreach ($programcateg['SUBCATEGS'] as $subcateg)
				                echo "<li><a href=" . Url::to(['create', 'id' => $subcateg['programcategory_id']]) . ">" . $subcateg['programcategory_programtitle']  . "</a></li>";
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
/*            ['attribute' => 'transport_teachers',
             'label' => Module::t('modules/schooltransport/app', 'Transportation Teachers')],
            ['attribute' => 'transport_students',
             'label' => Module::t('modules/schooltransport/app', 'Transportation Students')],*/
            // 'transport_students',
            // 'meeting_id',
            // 'school_id',

            ['class' => 'yii\grid\ActionColumn',
             'contentOptions' => ['class' => 'text-nowrap'],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
