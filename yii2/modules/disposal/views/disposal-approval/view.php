<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\modules\disposal\models\Disposal;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalApproval */

$this->title = DisposalModule::t('modules/disposal/app', 'View Approval');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Disposals Approvals'), 'url' => ['/disposal/disposal-approval/index']];
$this->params['breadcrumbs'][] = $this->title;

//echo "<pre>"; print_r($specializations); echo "</pre>"; die();
?>
<div class="disposal-approval-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="pull-right">
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->approval_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->approval_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'approval_regionaldirectprotocol',
            'approval_localdirectprotocol',
            'approval_notes',
            ['label' => 'Αρχείο Έγκρισης', 'format' => 'html', 'value' => Html::a(DisposalModule::t('modules/disposal/app', 'Donwload file'), ['disposal-approval/download', 'id' =>$model['approval_id']])],
            'approval_signedfile',
        ],
    ]) ?>

	<h3><?= DisposalModule::t('modules/disposal/app', 'Disposals'); ?></h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">
        	<thead>
        		<tr><th>Ονοματεπώνυμο</th>
        		<th>Ειδικότητα</th>
        		<th>Σχολείο Οργανικής</th>
        		<th>Σχολείο Διάθεσης</th>
        		<th>Ώρες</th>
        		<th>Διάρκεια</th>
        		</tr>
    		</thead>
            <?php foreach ($disposal_models as $index => $disposal_model): ?>
            	<tr>
            		<td><?php echo $teacher_models[$index]['teacher_surname'] . ' ' .  $teacher_models[$index]['teacher_name']; ?></td>
            		<td><?php echo $specializations[$index]['code'] . ', ' . $specializations[$index]['name']; ?></td>
            		<td><?php echo $disposal_schools[$index]['school_name']; ?></td>
            		<td><?php echo $organic_schools[$index]['school_name']; ?></td>
            		<td><?php echo ($disposal_model['disposal_hours'] == Disposal::FULL_DISPOSAL) ? DisposalModule::t('modules/disposal/app', 'Full time Disposal') : $disposal_model['disposal_hours']; ?></td>
            		<td><?php echo date_format(date_create($disposal_model['disposal_startdate']), 'd/m/Y') . ' - ' . date_format(date_create($disposal_model['disposal_enddate']), 'd/m/Y');; ?></td>
            	</tr>            	
            <?php endforeach;?>
        </table>
    </div> 
</div>