<?php
use app\modules\finance\Module;
use dosamigos\chartjs\ChartJs;

if(!is_null($withdrawals_expendituressum)) :

$grid_class = ($horizontal) ? 'col-lg-6' : '';
$separator = (!$horizontal) ? '<div><hr></div>' : '';
?>

<div class='<?= $grid_class ?>'>
    <?= ChartJs::widget(['type' => 'horizontalBar',
        
        'data' => [
            'labels' => $withdrawals_expendituressum['DECISION'],
            'datasets' => [
                [
                    'label' => Module::t('modules/finance/app', "Initial Amount"),
                    'backgroundColor' => 'blue', //$colors,
                    'data' => $withdrawals_expendituressum['INITIAL']
                ],
                [
                    'label' => Module::t('modules/finance/app', "Expenditures Sum"),
                    'backgroundColor' => 'red', //$colors,
                    'data' => $withdrawals_expendituressum['EXPENDED']
                ],
                [
                    'label' => Module::t('modules/finance/app', "Available Amount"),
                    'backgroundColor' => 'green', //$colors,
                    'data' => $withdrawals_expendituressum['AVAILABLE']
                ],
            ]
        ],
        'clientOptions' => ['title' => ['display' => true, 'text' => 'Στοιχεία αναλήψεων ΚΑΕ δαπάνης']],
        'options' => ['height' => 50+count($withdrawals_expendituressum['DECISION'])*35],
    ]);
?>
</div>
<?= $separator; ?>
<div class="table-responsive <?= $grid_class ?> ">          
	<table class="table table-bordered table-hover">
		<thead><tr class="info"><th colspan="4" style="text-align: center;">Στοιχεία αναλήψεων ΚΑΕ δαπάνης</th></tr></thead>
    	<thead>
            <tr>
                <th><?= Module::t('modules/finance/app', "Withdrawal") ?></th>
                <th><?= Module::t('modules/finance/app', "Initial Amount") ?></th>
                <th><?= Module::t('modules/finance/app', "Expenditures Sum") ?></th>
                <th><?= Module::t('modules/finance/app', "Available Amount") ?></th>
            </tr>
        </thead>
        <tbody>
        	<?php for ($i = 0; $i < count($withdrawals_expendituressum['DECISION']); $i++) {?>
                <tr>
                    <td><?= $withdrawals_expendituressum['DECISION'][$i]; ?></td>
                    <td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($withdrawals_expendituressum['INITIAL'][$i]); ?></td>
                    <td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($withdrawals_expendituressum['EXPENDED'][$i]); ?></td>
                    <td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($withdrawals_expendituressum['AVAILABLE'][$i]); ?></td>
                </tr>
            <?php }?>
        </tbody>
	</table>
</div>
<?php endif; ?>