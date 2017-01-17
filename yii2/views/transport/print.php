<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\Transport;

/* @var $this yii\web\View */
/* @var $model app\models\Transport */

$this->title = $model->information;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->information, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Print');
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
        <?= Html::a(Yii::t('app', 'Return to view'), ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Print approval'), ['reprint', 'id' => $model->id, 'ftype' => Transport::fapproval ], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to print this transport approval?'),
                'method' => 'post',
            ],
        ])
        ?>
        <?=
        Html::a(Yii::t('app', 'Print journal'), ['datesel', 'id' => $model->id, 'ftype' => Transport::fjournal ], [
            'class' => 'btn btn-success',
            'data' => [
                'method' => 'post',
            ],
        ])
        ?>
        <?=
        Html::a(Yii::t('app', 'Email journal'), ['emailjournal', 'id' => $model->id, 'ftype' => Transport::fjournal ], [
            'class' => 'btn btn-info',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to email this transport journal?'),
                'method' => 'post',
            ],
        ])
        ?>               
    </p>
    <?php if ($filename != null) : ?>
        <div class="alert alert-info" role="alert">Το-α αρχείο-α εκτύπωσης της μετακίνησης είναι διαθέσιμο-α για μεταφόρτωση.</div>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">Το-α αρχείο-α εκτύπωσης της μετακίνησης δεν είναι διαθέσιμο-α. Προσπαθήστε να το-α εκτυπώσετε ξανά.</div>
    <?php endif; ?>
    
    <?php
		$transportPrintDataProvider = new ArrayDataProvider([
                'allModels' => $model->transportPrints,
                'pagination' => [
                    'pagesize' => 10,
                ],
                'sort' => [
                    'attributes' => [
					'filename',
					'create_ts',
					//'send_ts',
					//'to_emails',					
                    ],
                ]
            ]);
    ?>
	<?php Pjax::begin(); ?>
	<?=
		GridView::widget([
			'dataProvider' => $transportPrintDataProvider,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
				['label' => Yii::t('app', 'Filename'),
					'attribute' => 'filename'],
				['label' => Yii::t('app', 'Created At'),
					'attribute' => 'create_ts'],
				[
                'class' => 'yii\grid\ActionColumn',
					'template' => '{show} {download}',
					'buttons' => [
						'download' => function ($url, $tpmodel, $key) use ($model) {
							return Html::a(
								'<span class="glyphicon glyphicon-download"></span>', Url::to(['/transport/download', 'id' => $model->id /* $model->transport */ , 'printid' => $tpmodel->id ]), [
									'title' => Yii::t('app', 'Download'),
									'data-confirm' => Yii::t('app', 'Are you sure you want to download this transport?'),
									'data-method' => 'post',
                                      ]
								);
						},
					]
				],
			],
		]);
	?>
	<?php Pjax::end(); ?>
    <p>
        <?= Html::a(Yii::t('app', 'Return to view'), ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete approval'), ['deleteprints', 'id' => $model->id, 'ftype' => Transport::fapproval], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete approval of this transport?'),
                'method' => 'post',
            ],
        ])
        ?>
        <?=
        Html::a(Yii::t('app', 'Delete journal'), ['deleteprints', 'id' => $model->id, 'ftype' => Transport::fjournal], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete journal of this transport?'),
                'method' => 'post',
            ],
        ])
        ?>
        <?=
        Html::a(Yii::t('app', 'Delete cover doc - report'), ['deleteprints', 'id' => $model->id, 'ftype' => Transport::freport], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete cover document and report of this transport?'),
                'method' => 'post',
            ],
        ])
        ?>
        <?=
        Html::a(Yii::t('app', 'Delete all'), ['deleteprints', 'id' => $model->id, 'ftype' => Transport::fall], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete all prints of this transport?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>
	
</div>
