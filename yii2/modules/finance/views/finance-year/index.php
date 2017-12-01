<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceYearSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->title = Yii::t('app', 'Finance Years');
$this->params['breadcrumbs'][] = $this->title;
//echo "<pre>"; print_r($dataProvider); echo"</pre>";
//die();
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-year-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Finance Year'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'value' => function ($dataProvider) {return $dataProvider->year_lock == 1 ? '<span class="glyphicon glyphicon-lock"></span>' : ' ';}
            ],
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}&nbsp;{update}&nbsp;{delete}&nbsp;{lock}&nbsp;{currentyear}',
             'buttons' => [
                                'view' => function ($url, $dataProvider) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'lead-view'),]);
                                },
                    
                                'update' => function ($url, $dataProvider) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => Yii::t('app', 'lead-update'),]);
                                },
                                'delete' => function ($url, $dataProvider) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                                        'title' => Yii::t('app', 'lead-delete'),
                                                        'data'=>['confirm'=>"Η διαγραφή του έτους είναι μη αναστρέψιμη ενέργεια. Είστε σίγουροι για τη διαγραφή;",
                                                        'method' => "post"]]);
                                },
                                'lock' => function ($url, $dataProvider) {
                                    if($dataProvider->year_lock == 0)
                                        return Html::a('<span class="glyphicon glyphicon-lock"></span>', $url, [
                                                        'title' => Yii::t('app', 'lead-lock'),
                                                        'data'=>['confirm'=>"Το κλείδωμα του έτους είναι μη αναστρέψιμη ενέργεια και έπειτα από αυτή καμία αλλαγή στο συγκεκριμένο έτος δεν θα είναι εφικτή. Είστε σίγουροι ότι θέλετε να κλειδώσετε το έτος;",
                                                        'method' => "post"]]);
                                },
                                'currentyear' => function ($url, $dataProvider) {
                                    if($dataProvider->year_iscurrent == 0) 
                                        return Html::a('<span class="glyphicon glyphicon-pushpin"></span>', $url, [
                                                        'title' => Yii::t('app', 'lead-currentyear'),
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
                                    $url = ['/finance/finance-year/lock', 'id'=> $dataProvider->year];
                                    return $url;
                                    
                                }
                                if ($action === 'currentyear') {
                                    $url = ['/finance/finance-year/current-year', 'id'=> $dataProvider->year];
                                    return $url;
                                    
                                } 
                    }
            ],
        ],
    ]); ?>
</div>
