<?php

use app\modules\schooltransport\Module;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\modules\schooltransport\models\SchtransportTransportstate;
use app\modules\schooltransport\models\SchtransportState;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\schooltransport\models\SchtransportTransportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$stateactions = '';
if($archived)
    $this->title .= Module::t('modules/schooltransport/app', 'Archived Transportations Approvals');
else{
    $stateactions = '<hr />{backwardstate} {forwardstate}';
    $this->title .= Module::t('modules/schooltransport/app', 'Transportations Approvals');
}
$this->params['breadcrumbs'][] = $this->title;

//echo "<pre>"; print_r($dataProvider); echo "</pre>";die();

?>
<div class="schtransport-transport-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p class="text-right">
		<?php 
		      if($archived):
                  echo Html::a(Module::t('modules/schooltransport/app', 'Active Transportations Approvals'), ['index'], ['class' => 'btn btn-primary']);
              else:
                  echo Html::a(Module::t('modules/schooltransport/app', 'Archive'), ['archive'], 
                                        ['class' => 'btn btn-success', 'data-method' => 'POST']);
              endif;
        ?>
    	<button type="button" class="btn btn-success" data-toggle="collapse" data-target="#programsCategs">
        	<?php echo Module::t('modules/schooltransport/app',  'Create Transportation'); ?>
        </button>
    </p>
    
    <div id="programsCategs" class="collapse">
        <div class="container-fluid well">
      		<div class="row">
				<?php 
				    foreach ($programcategs as $key=>$programcateg){
				        $sep = ($programcateg['PROGRAMCATEG_ALIAS'] == 'EUROPEAN_SCHOOL');
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
            //['class' => 'yii\grid\SerialColumn'],              
            ['attribute' => 'school_name',
             'label' => Module::t('modules/schooltransport/app', 'School Unit'),
             'headerOptions' => ['class'=> 'text-center'],
            ],
            ['attribute' => 'meeting_country',
             'label' => Module::t('modules/schooltransport/app', 'Transportation Country'),
             'headerOptions' => ['class'=> 'text-center']
            ],
            ['attribute' => 'meeting_city',
             'label' => Module::t('modules/schooltransport/app', 'Transportation City'),
             'headerOptions' => ['class'=> 'text-center'],
            ],
            ['attribute' => 'transport_startdate',
             'label' => Module::t('modules/schooltransport/app', 'Transportation Start'),
             'format' => ['date', 'php:d-m-Y'],
             'filter' => DateControl::widget([  'model' => $searchModel,
                                                'attribute' => 'transport_startdate',
                                                'type' => DateControl::FORMAT_DATE,
                                                'widgetOptions' => ['layout' => '{remove}{input}'],
                                            ]),
            'headerOptions' => ['class'=> 'text-center'],
            ],
            ['attribute' => 'transport_enddate',
             'label' => Module::t('modules/schooltransport/app', 'Transportation End'),
             'format' => ['date', 'php:d-m-Y'],
             'filter' => DateControl::widget([  'model' => $searchModel,
                                                'attribute' => 'transport_enddate',
                                                'type' => DateControl::FORMAT_DATE,
                                                'widgetOptions' => ['layout' => '{remove}{input}'],
                                            ]),    
             'headerOptions' => ['class'=> 'text-center'],
            ],
            /*['attribute' => 'meeting_startdate',
             'label' => Module::t('modules/schooltransport/app', 'Meeting Start'),
             'format' => ['date', 'php:d-m-Y'],
             'filter' => DateControl::widget([  'model' => $searchModel,
                                                'attribute' => 'meeting_startdate',
                                                'type' => DateControl::FORMAT_DATE,
                                                'widgetOptions' => ['layout' => '{remove}{input}'],
                ]),
             'headerOptions' => ['class'=> 'text-center'],
            ],
            ['attribute' => 'meeting_enddate',
             'label' => Module::t('modules/schooltransport/app', 'Meeting End'),
             'format' => ['date', 'php:d-m-Y'],
             'filter' => DateControl::widget([  'model' => $searchModel,
                                                'attribute' => 'meeting_enddate',
                                                'type' => DateControl::FORMAT_DATE,
                                                'widgetOptions' => ['layout' => '{remove}{input}'],
                ]),
             'headerOptions' => ['class'=> 'text-center'],
            ],*/
            ['attribute' => 'programcategory_programtitle',
             'label' => Module::t('modules/schooltransport/app', 'Program'),
             'headerOptions' => ['class'=> 'text-center'],
            ],
            ['attribute' => 'statescount',
             'label' => Module::t('modules/schooltransport/app', 'State'),
             'format' => 'html',
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class'=> 'text-center'],
             'value' => function($model){
                            $tmp1 = SchtransportTransportstate::findOne(['transport_id' => $model['transport_id'], 'state_id' => 1]);
                            $tmp2 = SchtransportTransportstate::findOne(['transport_id' => $model['transport_id'], 'state_id' => 2]);
                            $tmp3 = SchtransportTransportstate::findOne(['transport_id' => $model['transport_id'], 'state_id' => 3]);
             
                            $transport_state = SchtransportTransportstate::findOne(['transport_id' => $model['transport_id'], 'state_id' => 1]);                            
                            $state_commnents1 = SchtransportState::findOne(['state_id' => 1])['state_name'] . ': ' . 
                                                date_format(date_create($transport_state['transportstate_date']), 'd-m-Y') . ' (' . 
                                                $transport_state['transportstate_comment'] . ')';
                            $transport_state = SchtransportTransportstate::findOne(['transport_id' => $model['transport_id'], 'state_id' => 2]);
                            $state_commnents2 = SchtransportState::findOne(['state_id' => 2])['state_name'] . ': ' . 
                                                date_format(date_create($transport_state['transportstate_date']), 'd-m-Y') . ' (' . 
                                                $transport_state['transportstate_comment'] . ')';
                            $transport_state = SchtransportTransportstate::findOne(['transport_id' => $model['transport_id'], 'state_id' => 3]);
                            $state_commnents3 = SchtransportState::findOne(['state_id' => 3])['state_name'] . ': ' . 
                                                date_format(date_create($transport_state['transportstate_date']), 'd-m-Y') . ' (' . 
                                                $transport_state['transportstate_comment'] . ')';
                            
                            $url1 = Url::to(['/schooltransport/schtransport-transport/updatestate', 'state_id' => 1, 'transport_id' =>$model['transport_id']]);
                            $url2 = Url::to(['/schooltransport/schtransport-transport/updatestate', 'state_id' => 2, 'transport_id' =>$model['transport_id']]);
                            $url3 = Url::to(['/schooltransport/schtransport-transport/updatestate', 'state_id' => 3, 'transport_id' =>$model['transport_id']]);
                            $retvalue = ' ';                            
                                                                   
                            if ($model['statescount'] == 1) {
                                //$url = Url::to(['/schooltransport/schtransport-transport/updatestate', 'state_id' => 1, 'transport_id' =>$model['transport_id']]);
                                $retvalue = Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:red;" data-toggle="tooltip" data-html="true" title="' . $state_commnents1 . '"></span>',
                                                     $url1, ['title' => Module::t('modules/schooltransport/app', 'Forward to next state')]);
                            } elseif ($model['statescount'] == 2) {
                                //$url1 = Url::to(['/schooltransport/schtransport-transport/updatestate', 'state_id' => 1, 'transport_id' =>$model['transport_id']]);
                                //$url2 = Url::to(['/schooltransport/schtransport-transport/updatestate', 'state_id' => 2, 'transport_id' =>$model['transport_id']]);
                                $retvalue = Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:red;" data-toggle="tooltip" data-html="true" title="' . $state_commnents1 . '"></span>',
                                    $url1
                                    );
                                $retvalue .= '<br />';
                                $retvalue .= Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:orange; data-toggle="tooltip" data-html="true" title="' . $state_commnents2 . '"></span>',
                                    $url2
                                    );
                            } elseif ($model['statescount'] == 3) {
                                //$url1 = Url::to(['/schooltransport/schtransport-transport/updatestate', 'state_id' => 1, 'transport_id' =>$model['transport_id']]);
                                //$url2 = Url::to(['/schooltransport/schtransport-transport/updatestate', 'state_id' => 2, 'transport_id' =>$model['transport_id']]);
                                //$url3 = Url::to(['/schooltransport/schtransport-transport/updatestate', 'state_id' => 3, 'transport_id' =>$model['transport_id']]);
                                $retvalue = Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:red;" data-toggle="tooltip" data-html="true" title="' . $state_commnents1 . '"></span>', $url1);
                                $retvalue .= '<br />';
                                $retvalue .= Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:orange; data-toggle="tooltip" data-html="true" title="' . $state_commnents2 . '"></span>', $url2);
                                $retvalue .= '<br />';
                                $retvalue .= Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:green;" data-toggle="tooltip" data-html="true" title="' . $state_commnents3 . '"></span>', $url3);
                            }

                            return $retvalue;
                        }
            ],
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view} {update} {delete} {download} {downloadsigned} {archive}' . $stateactions,
              'buttons' => ['archive' => function ($url, $model) {
                                                        if(!$model['transport_isarchived'])
                                                            return Html::a('<span class="glyphicon glyphicon-save-file"></span>', $url,
                                                                ['title' => Module::t('modules/schooltransport/app', 'Archive transportation approval'),
                                                                    'data'=>['confirm'=>"Είστε σίγουροι ότι θέλετε να αρχειοθετήσετε την έγκριση μετακίνησης;",
                                                                             'data-method' => 'post']]);
                                                        else
                                                            return Html::a('<span class="glyphicon glyphicon-open-file"></span>', $url,
                                                                ['title' => Module::t('modules/schooltransport/app', 'Restore transportation approval'),
                                                                    'data'=>['confirm'=>"Είστε σίγουροι ότι θέλετε να επαναφέρετε την έγκριση μετακίνησης στις ενεργές;",
                                                                             'data-method' => 'post']]);
                                                    },
                           'downloadsigned' => function ($url, $model) {
                                                    if(!is_null($model['transport_signedapprovalfile']))
                                                        return Html::a('<span class="glyphicon glyphicon-lock"></span>', $url,
                                                                    ['title' => Module::t('modules/schooltransport/app', 'Download digitally signed file'),
                                                                     'data-method' => 'post']);
                                                    return '';
                                            },
                           'download' =>    function ($url, $model) {
                                                    return Html::a('<span class="glyphicon glyphicon-download"></span>', $url,
                                                                    ['title' => Module::t('modules/schooltransport/app', 'Download Decision'),
                                                                     'data-method' => 'post']);
                                            },
                           'forwardstate' => function ($url, $model) {
                                                if ($model['statescount'] < 3) {
                                                    return Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', $url,
                                                                    ['title' => Module::t('modules/schooltransport/app', 'Forward to next state')]);
                                                }
                                            },
                           'backwardstate' => function ($url, $model) {
                                                if ($model['statescount'] > 0) {
                                                    return Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', $url,
                                                                    ['title' => Module::t('modules/schooltransport/app', 'Backward to previous state'),
                                                                     'data'=>['confirm'=>Module::t('modules/schooltransport/app', "Are you sure you want to change the state of the transport's approval?"),
                                                                     'method' => "post"]
                                                                    ]);
                                                }
                                              }
                          ],
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
                if ($action === 'downloadsigned') {
                    $url = Url::to(['/schooltransport/schtransport-transport/downloadsigned', 'id' =>$model['transport_id']]);
                    return $url;
                }
                if ($action === 'archive') {
                    $url = "";
                    if(!$model['transport_isarchived'])
                        $url = Url::to(['/schooltransport/schtransport-transport/archive', 'id' =>$model['transport_id']]);
                    else
                        $url = Url::to(['/schooltransport/schtransport-transport/restore', 'id' =>$model['transport_id']]);
                    return $url;
                }
                if ($action === 'backwardstate') {
                    $url = Url::to(['/schooltransport/schtransport-transport/backwardstate', 'id' =>$model['transport_id']]);
                    return $url;
                }
                if ($action === 'forwardstate') {
                    $url = Url::to(['/schooltransport/schtransport-transport/forwardstate', 'id' =>$model['transport_id']]);
                    return $url;
                }
            },
            'contentOptions' => ['class'=> 'text-center text-nowrap'],
            ],
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model) {return ['value' => $model['transport_id']];}
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
