<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\Disposal */

$url = 'index?archived=' . $archived; 
$this->title = DisposalModule::t('modules/disposal/app', 'View Teacher\'s Disposal');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ($archived) ? ['label' => DisposalModule::t('modules/disposal/app', 'Processed Disposals'), 'url' => [$url]] 
                                             : ['label' => DisposalModule::t('modules/disposal/app', 'Disposals for Approval'), 'url' => [$url]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-view">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php if(!$archived):?>
        <p>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->disposal_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->disposal_id], [
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
            'disposal_id',
            'disposal_startdate',
            'disposal_enddate',
            'disposal_hours',
            'teacher_id',
            'school_id',
        ],
    ]) ?>

</div>
