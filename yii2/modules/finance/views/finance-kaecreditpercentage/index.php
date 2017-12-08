<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\finance\Module;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceKaecreditpercentageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('modules/finance/app', 'RCN Credits Percentages');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = $this->title;

$columnsNum = 3;
$kaesCount = count($kaes);
$kaesSubListCount = ceil($kaesCount/$columnsNum);
$kaesListDivide = array();

for($i = 0; $i < $columnsNum; $i++)
    for($j = 0; $j < $kaesSubListCount; $j++)
    {//echo strval(($kaesSubListCount*$i + $j)) . " " . $kaesCount . " "  . strval($kaesSubListCount*$i + $j) . "<br />";
        if(($kaesSubListCount*$i + $j) >= $kaesCount)
            break;
        $kaesListDivide[$i][$j] = $kaes[$kaesSubListCount*$i + $j]; 
    }
?>
<div class="finance-kaecreditpercentage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#kaesList">
        	<?php echo Module::t('modules/finance/app', 'Attribute percentage to RCN credit')?>
        </button>

    </p>
	<div id="kaesList" class="collapse">
        <div class="container-fluid well">
      		<div class="row">
      			<?php foreach ($kaesListDivide as $kaeList) : ?>
      						<div class="col-lg-<?php echo 12/$columnsNum; ?>">
      							<?php foreach ($kaeList as $kaeListItem): ?> 
                                        <p><a href='/index.php/finance/finance-kaecreditpercentage/create?id=<?php echo $kaeListItem->kae_id; ?>'><span class="label label-primary"><?= $kaeListItem->kae_id; ?></span>
                                        <?php echo $kaeListItem->kae_title;?></a>
                                        </p>
                                <?php endforeach;?>
      						</div>		
      			<?php endforeach;?>
      			
      			
    		</div>
    	</div>
	</div>
	
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
                                                    ['title' => Module::t('modules/finance/app', 'Update'),]);
                                                },
                                'delete' => function ($url, $model) {
                                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, 
                                                    ['title' => Module::t('modules/finance/app', 'Delete'),
                                                        'data'=>['confirm'=>Module::t('modules/finance/app', "The deletion of the percentage attribution of a RCN credit is irreversible action. Are you sure you want to delete this item?"),
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
