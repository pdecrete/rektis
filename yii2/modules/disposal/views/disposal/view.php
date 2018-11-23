<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\Disposal */

$archived = $model['archived'];
$rejected = $model['disposal_rejected'];

$url = 'index?archived=' . $archived . '&rejected=' . $rejected; 
$this->title = DisposalModule::t('modules/disposal/app', 'View Teacher\'s Disposal');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['index']];
if ($archived)
    $this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Processed Disposals'), 'url' => [$url]];
else if ($rejected)
    $this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Rejected Disposals'), 'url' => [$url]];
else
    $this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Disposals for Approval'), 'url' => [$url]];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-view">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php if(!$archived && !$rejected):?>
        <p class="text-right">
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model['disposal_id']], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model['disposal_id']], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif;?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['label'=> DisposalModule::t('modules/disposal/app', 'Teacher'), 'value'=>$model['teacher_id']],
            ['label'=> DisposalModule::t('modules/disposal/app', 'Organic Post'), 'value'=>$model['Organic Post']],
            ['label'=> DisposalModule::t('modules/disposal/app', 'Disposal School'), 'value'=>$model['school_id']],
            ['label'=> DisposalModule::t('modules/disposal/app', 'Disposal Duration'), 'value'=>$model['disposal_startdate'] . ' - ' . $model['disposal_enddate']],
            ['label'=> DisposalModule::t('modules/disposal/app', 'Disposal Hours'), 'value'=>$model['disposal_hours']],
            ['label'=> DisposalModule::t('modules/disposal/app', 'Disposal Reason'), 'value'=>$model['disposalreason_id']],
            ['label'=> DisposalModule::t('modules/disposal/app', 'Disposal Duty'), 'value'=>$model['disposalworkobj_id']],            
        ],
    ]) ?>

</div>
