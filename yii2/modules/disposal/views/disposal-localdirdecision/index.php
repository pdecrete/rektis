<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\disposal\models\DisposalLocaldirdecisionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->title = DisposalModule::t('modules/disposal/app', 'Local Directorate Suggestions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-localdirdecision-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">
        <?= Html::a(DisposalModule::t('modules/disposal/app', 'Create Local Directorate Suggestion'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'localdirdecision_protocol',
            'localdirdecision_subject',
            'localdirdecision_action',
            //'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'deleted',
            // 'archived',

            ['class' => 'yii\grid\ActionColumn',
             'contentOptions' => ['class' => 'text-nowrap'],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
