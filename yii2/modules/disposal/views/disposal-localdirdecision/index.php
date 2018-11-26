<?php

use app\modules\disposal\DisposalModule;
use app\modules\disposal\widgets\ButtonShortcuts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\disposal\models\DisposalLocaldirdecisionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->title = DisposalModule::t('modules/disposal/app', 'Local Directorate Decisions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-localdirdecision-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="text-right">
        <?= Html::a(DisposalModule::t('modules/disposal/app', 'Create Decision'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php echo ButtonShortcuts::widget(); ?>
    </div><br />
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'directorate_shortname',
             'label' => DisposalModule::t('modules/disposal/app', 'Directorate'),
            ],            
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
             'urlCreator' => function ($action, $model) {
                if ($action === 'delete') {
                    $url = Url::to(['/disposal/disposal-localdirdecision/delete', 'id' =>$model['localdirdecision_id']]);
                    return $url;
                }
                if ($action === 'update') {
                    $url = Url::to(['/disposal/disposal-localdirdecision/update', 'id' =>$model['localdirdecision_id']]);
                    return $url;
                }
                if ($action === 'view') {
                    $url = Url::to(['/disposal/disposal-localdirdecision/view', 'id' =>$model['localdirdecision_id']]);
                    return $url;
                }
             },
             'contentOptions' => ['class' => 'text-nowrap'],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
