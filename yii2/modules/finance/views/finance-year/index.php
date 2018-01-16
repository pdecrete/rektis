<?php

use app\modules\finance\Module;
use app\modules\finance\components\Integrity;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceYearSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->title = Module::t('modules/finance/app', 'Finance Years');
$this->params['breadcrumbs'][] = $this->title;

$this->render('/default/infopanel'); 

?>

<div class="finance-year-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p style="text-align: right;">
        <?= Html::a(Module::t('modules/finance/app', 'Create Finance Year'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'year',
            'year_credit',
            [   'attribute' => 'year_iscurrent',
                'format' => 'html',
                'value' => function ($dataProvider) {return $dataProvider->year_iscurrent == 1 ? '<span class="glyphicon glyphicon-pushpin"></span>' : ' ';}
            ],
            [   'attribute' => 'year_lock',
                'format' => 'html',
                'value' => function ($dataProvider) {return $dataProvider->year_lock == 1 ? '<span class="glyphicon glyphicon-ok"></span>' : ' ';}
            ],
            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'text-nowrap'
                ],
             'template' => '{view}&nbsp;{update}&nbsp;{delete}&nbsp;{lock}&nbsp;{currentyear}',
             'buttons' => [
                                'view' => function ($url, $dataProvider) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Module::t('modules/finance/app', 'View'),]);
                                },
                    
                                'update' => function ($url, $dataProvider) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Module::t('modules/finance/app', 'Update'),]);
                                },
                                'delete' => function ($url, $dataProvider) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'title' => Module::t('modules/finance/app', 'Delete'),
                                                        'data'=>['confirm'=>"Η διαγραφή του έτους είναι μη αναστρέψιμη ενέργεια. Είστε σίγουροι για τη διαγραφή;",
                                                        'method' => "post"]]);
                                },
                                'lock' => function ($url, $dataProvider) {
                                    if($dataProvider->year_lock == 0)
                                        return Html::a('<span class="glyphicon glyphicon-lock" style="color:red"></span>', $url, [
                                            'title' => Module::t('modules/finance/app', 'Lock'),
                                                        'data'=>['confirm'=>"Κλειδώνοντας το έτος καμία αλλαγή δεν θα είναι εφικτή σε αυτό. Είστε σίγουροι ότι θέλετε να κλειδώσετε το έτος;",
                                                        'method' => "post"]]);
                                        else
                                            return Html::a('<span class="glyphicon glyphicon-lock" style="color:green"></span>', $url, [
                                                'title' => Module::t('modules/finance/app', 'Unlock'),
                                                'data'=>['confirm'=>"Είστε σίγουροι ότι θέλετε να ξεκλειδώσετε το έτος;",
                                                    'method' => "post"]]);
                                },
                                'currentyear' => function ($url, $dataProvider) {
                                    if($dataProvider->year_iscurrent == 0) 
                                        return Html::a('<span class="glyphicon glyphicon-pushpin"></span>', $url, [
                                            'title' => Module::t('modules/finance/app', 'Set as currently working'),
                                                        'data'=>['confirm'=>"Είστε σίγουροι ότι θέλετε να ορίσετε ώς έτος εργασίας το έτος " . $dataProvider->year . ";",
                                                        'method' => "post"]]);
                                }
                    
                    ],
                    'urlCreator' => function ($action, $dataProvider, $key, $index) {
                                if ($action === 'view') {
                                    $url ='/finance/finance-year/view?id='.$dataProvider->year;
                                    return $url;
                                }
                                
                                if ($action === 'update') {
                                    $url ='/finance/finance-year/update?id='.$dataProvider->year;
                                    return $url;
                                }
                                if ($action === 'delete') {
                                    $url = ['/finance/finance-year/delete', 'id'=> $dataProvider->year];
                                    return $url;
                                }
                                if ($action === 'lock') {
                                    if($dataProvider->year_lock == 0){
                                        $url = ['/finance/finance-year/lock', 'id'=> $dataProvider->year];
                                        return $url;
                                    }
                                    else{
                                        $url = ['/finance/finance-year/unlock', 'id'=> $dataProvider->year];
                                        return $url;
                                    }
                                    
                                }
                                if ($action === 'currentyear') {
                                    $url = ['/finance/finance-year/current-year', 'id'=> $dataProvider->year];
                                    return $url;
                                    
                                } 
                    }
            ],
        ],
    ]); 
    
    ?>
</div>
