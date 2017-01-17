<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
use app\models\Transport;
//use yii\data\ArrayDataProvider;
//use yii\grid\GridView;
//use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Transport */

$this->title = $model->information;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
if ($model->deleted) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger',
        ],
        'body' => Yii::t('app', 'This transport is marked as deleted.'),
    ]);
}
?>
<div class="transport-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?php if ($model->locked == False) { ?>
			<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= 
			Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
					'method' => 'post',
				],
			]) 
			?>
        <?php } else { 
					echo " <h2><small>Αυτή η μετακίνηση είναι κλειδωμένη επειδή έχει χρησιμοποιηθεί σε αποστολή πληρωμής εξόδων κίνησης.</small></h2>  ";
				} ?>
        <?=
        Html::a(Yii::t('app', 'Transport file'), ['print', 'id' => $model->id, 'ftype' => Transport::fapproval ], [
            'class' => 'btn btn-warning',
            'data' => [
                //'confirm' => Yii::t('app', 'Are you sure you want to print this transport?'),
                'method' => 'post',
            ],
        ])
        ?>

    </p>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#application" aria-controls="application" role="tab" data-toggle="tab"><?= Yii::t('app', 'Application') ?></a></li>
        <li role="presentation"><a href="#approval" aria-controls="approval" role="tab" data-toggle="tab"><?= Yii::t('app', 'Approval') ?></a></li>
        <li role="presentation"><a href="#money" aria-controls="money" role="tab" data-toggle="tab"><?= Yii::t('app', 'Economic') ?></a></li>
    </ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade-in active" id="application">

		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				// 'id',
				[
					'label' => $model->getAttributeLabel('employee'),
					'value' => $model->employee0 ? $model->employee0->fullname . ' ' . Html::a('<span class="glyphicon glyphicon-chevron-right"></span>', ['/employee/view', 'id' => $model->employee], ['class' => 'btn btn-primary btn-xs', 'role' => 'button']) : null,
					'format' => 'raw'
				],
				[
					'label' => $model->getAttributeLabel('start_date'),
					'value' => Yii::$app->formatter->asDate($model->start_date, 'long')
				],
				[
					'label' => $model->getAttributeLabel('end_date'),
					'value' => Yii::$app->formatter->asDate($model->end_date, 'long')
				],
				'reason',
				[
					'label' => $model->getAttributeLabel('from_to'),
					'value' => $model->fromTo ? $model->fromTo->name  : null
				],
				'base',
				[
					'label' => $model->getAttributeLabel('mode'),
					'value' => $model->mode0 ? $model->mode0->name  : null
				],
				'days_applied',
				'ticket_value:currency',
				'night_reimb:currency',
				'accompanying_document',
			],
		]) ?>
		</div>
		<div role="tabpanel" class="tab-pane fade-in" id="approval">
            <?=
            DetailView::widget([
			'model' => $model,
			'attributes' => [
				[
					'label' => $model->getAttributeLabel('type'),
					'value' => $model->type0 ? $model->type0->name : null
				],
				[
					'label' => $model->getAttributeLabel('fund1'),
					'value' => Yii::t('app', '{kae} ({name} - {date}) - {service_n}', [							
									'kae' => $model->transportFund1 ? $model->transportFund1->kae : null,
									'name' => $model->transportFund1 ? $model->transportFund1->name : null,
									'date' => \Yii::$app->formatter->asDate($model->transportFund1 ? $model->transportFund1->date : null),
									'service_n' => $model->transportFund1 ? $model->transportFund1->service0->name : null,
									])
				],
				[
					'label' => $model->getAttributeLabel('fund2'),
					'value' => Yii::t('app', '{kae} ({name} - {date}) - {service_n}', [							
									'kae' => $model->transportFund2 ? $model->transportFund2->kae : null,
									'name' => $model->transportFund2 ? $model->transportFund2->name : null,
									'date' => \Yii::$app->formatter->asDate($model->transportFund2 ? $model->transportFund2->date : null),
									'service_n' => $model->transportFund2 ? $model->transportFund2->service0->name : null,
									])
				],
				[
					'label' => $model->getAttributeLabel('fund3'),
					'value' => Yii::t('app', '{kae} ({name} - {date}) - {service_n}', [							
									'kae' => $model->transportFund3 ? $model->transportFund3->kae : null,
									'name' => $model->transportFund3 ? $model->transportFund3->name : null,
									'date' => \Yii::$app->formatter->asDate($model->transportFund3 ? $model->transportFund3->date : null),
									'service_n' => $model->transportFund3 ? $model->transportFund3->service0->name : null,
									])
				],
				'decision_protocol',
				'decision_protocol_date:date',
				'application_protocol',
				'application_protocol_date:date',
				'application_date:date',
				'extra_reason', 
			],
			]) ?>
		</div>
		<div role="tabpanel" class="tab-pane fade-in" id="money">
            <?=
            DetailView::widget([
			'model' => $model,
			'attributes' => [
				'klm',
				'klm_reimb:currency',
				'days_out',
				'day_reimb:currency',
				'nights_out',
				'reimbursement:currency',
				'mtpy:currency',
				'pay_amount:currency',
				'code719:currency',
				'code721:currency',
				'code722:currency',
				[
					'label' => $model->getAttributeLabel('count_flag'),
					'value' => Yii::t('app', '{boxstate}', [							
									'boxstate' => ($model->count_flag == 1) ? Yii::t('app', 'YES') : Yii::t('app', 'NO'),
									])
				],
				'expense_details',
				'comment:ntext',
				'create_ts',
				'update_ts',
			],
			]) ?>
		</div>

    </div>
</div>
