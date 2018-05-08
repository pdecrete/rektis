<?php

use app\modules\schooltransport\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\schooltransport\models\SchoolunitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->title = Module::t('modules/schooltransport/app', 'School Units');
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="schoolunit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">
        <?= Html::a(Module::t('modules/schooltransport/app', 'Update School Units Details'), ['massupdate'], 
            ['id' =>'massUpdateButton', 'class' => 'btn btn-success', 
             'onclick' => '(function () { document.getElementById("massUpdateButton").innerHTML = "Γίνεται ενημέρωση των στοιχείων από το myschool..."; })();']) ?>
        <!--<?= Html::a(Module::t('modules/schooltransport/app', 'Create School Unit'), ['create'], ['class' => 'btn btn-success']) ?>-->
    </p>
<?php Pjax::begin(); ?>    
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'school_id',
            'school_name',
            //'directorate_id',
            ['attribute' => 'directorate_name',
             'label' => Module::t('modules/schooltransport/app', 'Directorate of Education')
            ],
            /*
            ['attribute' => 'directorate_id',
                'label' => Module::t('modules/finance/app', 'Educational Directorate'),
                'value' => function ($dataProvider) {
                    return $dataProvider
                },
                'headerOptions' => ['class'=> 'text-center'],
                'contentOptions' => ['class' => 'text-right']
            ],                
            ['class' => 'yii\grid\ActionColumn',
             'contentOptions' => ['class' => 'text-nowrap'],
             'template' => '{update} {delete}',
             'urlCreator' => function ($action, $model) {
                                 if ($action === 'delete') {
                                     $url = Url::to(['/schooltransport/schoolunit/delete', 'id' =>$model['school_id']]);
                                     return $url;
                                 }
                                 if ($action === 'update') {
                                     $url = Url::to(['/schooltransport/schoolunit/update', 'id' =>$model['school_id']]);
                                     return $url;
                                 }
                             }
            ],*/
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
