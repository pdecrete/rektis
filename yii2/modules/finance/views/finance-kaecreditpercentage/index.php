<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceKaecreditpercentageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Finance Kaecreditpercentages');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = $this->title;
//echo "<pre>"; var_dump($dataProvider); echo "</pre>";die();

?>
<div class="finance-kaecreditpercentage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Finance Kaecreditpercentage'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'kae_id',
            'kae_title',
            [   'attribute' => 'kaecredit_amount',
                'format' => 'html',
                'value' => function ($model) {return Money::toCurrency($model['kaecredit_amount']);}
            ],
            [   'attribute' => 'kaeperc_percentage',
                'format' => 'html',
                'value' => function ($model) {return Money::toPercentage($model['kaeperc_percentage']);}
            ],
            'kaeperc_date',
            'kaeperc_decision',
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{update}&nbsp;{delete}',
             'buttons' =>   [   'update' => function ($url, $model) {
                                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, 
                                                                ['title' => Yii::t('app', 'lead-update'),]);
                                                },
                                'delete' => function ($url, $model) {
                                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, 
                                                    ['title' => Yii::t('app', 'lead-delete'),
                                                    'data'=>['confirm'=>"Η διαγραφή του έτους είναι μη αναστρέψιμη ενέργεια. Είστε σίγουροι για τη διαγραφή;",
                                                        'method' => "post"]]);
                                                },
                            ],
                            'urlCreator' => function ($action, $model) {
                                                if ($action === 'update') {
                                                    $url ='/finance/finance-kaecreditpercentage/update?id=' . $model['kaeperc_id'];
                                                    return $url;
                                                }
                                                if ($action === 'delete') {
                                                    $url = '/finance/finance-kaecreditpercentage/delete?id=' . $model['kaeperc_id'];
                                                    return $url;
                                                }
                                            }
            ],
        ],
    ]); ?>
</div>
