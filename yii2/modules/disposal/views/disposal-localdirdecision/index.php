<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\disposal\models\DisposalLocaldirdecisionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/modules/disposal/', 'Disposal Localdirdecisions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-localdirdecision-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app/modules/disposal/', 'Create Disposal Localdirdecision'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'localdirdecision_id',
            'localdirdecision_protocol',
            'localdirdecision_subject',
            'localdirdecision_action',
            'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'deleted',
            // 'archived',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
