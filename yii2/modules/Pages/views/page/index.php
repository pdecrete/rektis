<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\Email\components\EmailButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Σελίδες';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="page-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Νέο σελίδα', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php 
    echo EmailButtonWidget::widget([
        'template' => 'mail1',
        // 'from' => 'info@pdecrete.gr',
        'to' => [
            'spapad@sch.gr'
        ],
        'cc' => [
            'spapad@outlook.com'
        ],
        'label' => 'Στείλε email'
    ]);
     ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'identity',
            'title',
            'attribute' => 'created_at:datetime',
            'updated_at:datetime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);

    ?>
</div>