<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\disposal\models\DisposalApprovalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = DisposalModule::t('modules/disposal/app', 'Disposals Approvals');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-approval-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p class="text-right">
    	<?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposals for Approval'), ['disposal/index'], ['class' => 'btn btn-primary']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'approval_regionaldirectprotocol',
            //'approval_localdirectprotocol',
            'approval_notes',
            'created_at',
            'updated_at',
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view} {update} {delete} {download} {republish}',//, {downloadsigned}',
             'buttons' => ['download' => function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-download"></span>', $url,
                                                ['title' => DisposalModule::t('modules/disposal/app', 'Download Decision'),
                                                    'data-method' => 'post']);
                                            },
                           'republish' => function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-repeat"></span>', $url,
                                                ['title' => DisposalModule::t('modules/disposal/app', 'Republish'),
                                                    'data-method' => 'post']);
                                            },
                ],
                'urlCreator' => function ($action, $model) {
                                    if ($action === 'delete') {
                                        $url = Url::to(['/disposal/disposal-approval/delete', 'id' =>$model['approval_id']]);
                                        return $url;
                                    }
                                    if ($action === 'update') {
                                        $url = Url::to(['/disposal/disposal-approval/update', 'id' =>$model['approval_id']]);
                                        return $url;
                                    }
                                    if ($action === 'view') {
                                        $url = Url::to(['/disposal/disposal-approval/view', 'id' =>$model['approval_id']]);
                                        return $url;
                                    }
                                    if ($action === 'download') {
                                        $url = Url::to(['/disposal/disposal-approval/download', 'id' =>$model['approval_id']]);
                                        return $url;
                                    }
                                    if ($action === 'republish') {
                                        $url = Url::to(['/disposal/disposal-approval/republish', 'id' =>$model['approval_id']]);
                                        return $url;
                                    }
                                },
             'contentOptions' => ['class'=> 'text-center text-nowrap'],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
