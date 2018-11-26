<?php

use app\modules\disposal\DisposalModule;
use app\modules\disposal\widgets\ButtonShortcuts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\disposal\models\DisposalApprovalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = ($archived == 0) ? 'Disposals Approvals' : 'Archived Approvals';
$this->title =  DisposalModule::t('modules/disposal/app', $title);
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = $this->title;

$actioncolumn_template = ($archived == 0) ? '{view} {update} {delete} {download} {republish}' : '{view}';
?>
<?=Html::beginForm(['archiveform'], 'post');?>
<div class="disposal-approval-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>
    	<div class="text-right">
    		<?php if (!$archived) :?>
    			<?= Html::a(DisposalModule::t('modules/disposal/app', 'Archive'), ['massarchive', 'archive' => 1], ['class' => 'btn btn-success', 'data-method' => 'POST']) ?>
			<?php else :?>
				<?= Html::a(DisposalModule::t('modules/disposal/app', 'Restore'), ['massarchive', 'archive' => 0], ['class' => 'btn btn-success', 'data-method' => 'POST']) ?>
			<?php endif;?>
			<?php echo ButtonShortcuts::widget(); ?>
    	</div><br />	
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [ 'class' => 'yii\grid\CheckboxColumn',
              'checkboxOptions' => function ($model) {
                  return ['value' => $model['approval_id']];
              }
            ],
            //['class' => 'yii\grid\SerialColumn'],
            'approval_regionaldirectprotocol',
            //'approval_localdirectprotocol',
            'approval_notes',
            ['attribute' => 'created_at',
             'format' => ['datetime', 'php:d-m-Y H:i']
            ],
            ['attribute' => 'updated_at',
             'format' => ['datetime', 'php:d-m-Y H:i']
            ],
            ['class' => 'yii\grid\ActionColumn',
             'template' => $actioncolumn_template,
             'buttons' => ['download' => function ($url, $model) {
                 return Html::a(
                                                    '<span class="glyphicon glyphicon-download"></span>',
                                                                $url,
                                                                ['title' => DisposalModule::t('modules/disposal/app', 'Download Decision'), 'data-method' => 'post']
                                                );
             },
                           'republish' => function ($url, $model) {
                               return Html::a(
                                                    '<span class="glyphicon glyphicon-repeat"></span>',
                                                                $url,
                                                                ['title' => DisposalModule::t('modules/disposal/app', 'Republish'), 'data-method' => 'post']
                                                );
                           },
                            'republish' => function ($url, $model) {
                                return Html::a(
                                                '<span class="glyphicon glyphicon-repeat"></span>',
                                                $url,
                                                ['title' => DisposalModule::t('modules/disposal/app', 'Republish'), 'data-method' => 'post']
                                            );
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
<?= Html::endForm();?>