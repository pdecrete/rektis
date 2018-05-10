<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Applications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'call_id',
            'teacher_board_id',
            'agreed_terms_ts',
            'state',
            // 'state_ts',
            // 'reference:ntext',
            // 'created_at',
            // 'updated_at',
            // 'deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>