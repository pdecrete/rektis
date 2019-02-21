<?php
/* @var $this yii\web\View */
use dosamigos\chartjs\ChartJs;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\models\LeaveStatistic;

$script = "function chartjsToImage(){                
                var canvas = document.getElementsByTagName('canvas');
                var dataURL = canvas[0].toDataURL('image/png').replace('image/png', 'image/octet-stream');;
                var exportStatisticForm = document.getElementById('exportStatistic');
                hiddenImageData = document.createElement('input');
                hiddenImageData.type = 'hidden';
                hiddenImageData.name = 'image_data';
                hiddenImageData.value = dataURL;

                var tableData = document.getElementById('tableData').innerHTML;
                hiddenTableData = document.createElement('input');
                hiddenTableData.type = 'hidden';
                hiddenTableData.name = 'table_data';
                hiddenTableData.value = tableData;
                exportStatisticForm.appendChild(hiddenImageData);
                exportStatisticForm.appendChild(hiddenTableData);
           }";
$this->registerJs($script, View::POS_HEAD);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transports'), 'url' => ['/transport/index']];
$this->title = Yii::t('app', 'Statistics');
$this->params['breadcrumbs'][] = $this->title;

$current_year = date('Y');
//echo "<pre>"; print_r($result_data); echo "</pre>";die();
//echo $chart_title; die();
?>

<div class="text-right">
        <div class="btn-group">
      		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
      			<?= Yii::t('app', 'Export leaves to Excel file'); ?> <span class="caret"></span>
  			</button>
  			<ul class="dropdown-menu" role="menu">
    			<li><a href="<?= Url::to(['transport-statistic/exportexcel', 'year' => $current_year]);?>">
					<?= Yii::t('app', 'Current year') .  ' ('. $current_year . ')'; ?></a>
				</li>
    			<li><a href="<?= Url::to(['transport-statistic/exportexcel', 'year' => ($current_year-1)]);?>">
    				<?= Yii::t('app', 'Previous year'); ?>
    				</a>
				</li>
    			<li><a href="<?= Url::to(['transport-statistic/exportexcel', 'year' => -1]);?>">
					<?= Yii::t('app', 'All years'); ?>
					</a>
				</li>
  			</ul>
    	</div>
</div>


<?php ?>
<h1>Επιλογή Παραμέτρων</h1>
<div class="well container-fluid">
    <div class="col-lg-9"> 
    <?php $form = ActiveForm::begin(); ?>
    	<div class="row">
        	<div class="col-lg-6">
                <?= $form->field($model, 'statistic_year')->widget(Select2::classname(), [
                    'data' => $years,
                    'options' => ['multiple' => true, 'placeholder' => Yii::t('app', 'Select year...')],
                    ])->label('Έτος'); 
                ?>
        	</div>
        	<div class="col-lg-6">
    	        <?= $form->field($model, 'statistic_expendituretype')->widget(Select2::classname(), [
    	            'data' => $expendituretypes,
                    'options' => ['placeholder' => Yii::t('app', 'Select transport expenditure type...')],
                    ])->label('Δαπάνη Μετακίνησης'); 
    	        ?>
        	</div>
    	</div>
    	<div class="row">
        	<div class="col-lg-6">
    	        <?= $form->field($model, 'statistic_specialisation')->widget(Select2::classname(), [
    	            'data' => $specialisations,
                    'options' => ['placeholder' => Yii::t('app', 'Select specialisation...')],
                    ])->label('Ειδικότητα'); 
                ?>
        	</div>
			<div class="col-lg-6">
                <?= $form->field($model, 'statistic_employee')->widget(Select2::classname(), [
                    'data' => $employees,
                    'options' => ['placeholder' => Yii::t('app', 'Select employee...')],
                    ])->label('Εργαζόμενος'); 
                ?>
        	</div>
    	</div>
    	<div class="row">
        	<div class="col-lg-6">
                <?= $form->field($model, 'statistic_positionunit')->widget(Select2::classname(), [
                    'data' => $positionunits,
                    'options' => ['placeholder' => Yii::t('app', 'Select organism...')],
                    ])->label('Υπηρεσία'); 
                ?>    	
        	</div>
        	<div class="col-lg-6">
                <?= $form->field($model, 'statistic_days')->widget(Select2::classname(), [
                    'data' => $days,
                    'options' => ['placeholder' => Yii::t('app', 'Select days number...')],
                    ])->label('Πλήθος ημερών μετακίνησης'); 
                ?>    	
        	</div>
    	</div>
    	<div class="row">
    		<div class="col-lg-6">
                <?= $form->field($model, 'statistic_daysout')->widget(Select2::classname(), [
                    'data' => $daysout,
                    'options' => ['placeholder' => Yii::t('app', 'Select away days number...')],
                    ])->label('Πλήθος ημερών εκτός έδρας'); 
                ?>    	
        	</div>
        	<div class="col-lg-6">
                <?= $form->field($model, 'statistic_nightsout')->widget(Select2::classname(), [
                    'data' => $nightsout,
                    'options' => ['placeholder' => Yii::t('app', 'Select overnights number...')],
                    ])->label('Πλήθος διανυκτερεύσεων'); 
                ?>    	
        	</div>
    	</div>	    
      	<div class="row">
        	<div class="col-lg-6">
                <?= $form->field($model, 'statistic_charttype')->widget(Select2::classname(), [
                    'data' => $chart_types,
                    'options' => ['placeholder' => Yii::t('app', 'Select chart type...')],
                    ])->label('Τύπος Γραφήματος'); 
                ?>
        	</div>	
        	<div class="col-lg-6">
                <?= $form->field($model, 'statistic_groupby')->widget(Select2::classname(), [
                    'data' => $groupby_options,
                    'options' => ['placeholder' => Yii::t('app', 'Select grouping method...')],
                    ])->label('Ομαδοποίηση ανά'); 
                ?>
        	</div>
      	</div>
    	<div class="row pull-right">    
            <div class="form-group col-lg-6">  
                <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    
    <?php ActiveForm::end(); ?>
    </div>
    <div class="col-lg-3">
    	<div class="alert alert-info">
  			<strong>Σημείωση</strong> <p>Τα στατιστικά που σχετίζονται με τη υπηρεσία στην οποία υπηρετούν οι εργαζόμενοι συσχετίζονται με την τρέχουσα υπηρεσιακή κατάσταση των εργαζομένων. </p><p>Για παράδειγμα αν ένας
  			εργαζόμενος βρισκεται σε διαφορετική υπηρεσία από αυτή που βρισκόταν σε προηγούμενα έτη, τα στατιστικά που βγαίνουν, συσχετίζονται με την τρέχουσα υπηρεσία του και άρα οι μετακινήσεις τους
  			υπολογίζονται ως να έχουν γίνει από την υπηρεσία που βρίσκεται τώρα.</p>
		</div>
	</div>
</div>

<div id="canvasChart" class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?php   $max_result = max($result_data['TRANSPORTS_COUNT']); 
            
                    $results_count = count($result_data['TRANSPORTS_COUNT']);
                    $fontsize = 12;
                    $minrotation = 0;
                    if($results_count > 15 && $results_count <= 30)
                        $fontsize = 10;
                    else if($results_count > 30) {
                        $fontsize = 9;
                        $minrotation = 90;
                    }
                        
                    $stepSize = ceil($max_result/10); 
                    $max_value = $stepSize*10;
                    $height = 100;
                    $clientOptions['clientOptions'] = ''; 
                    if($selected_chart_type == LeaveStatistic::CHARTTYPE_HORIZONTALBAR){
                        $height = 15*count($result_data['TRANSPORTS_COUNT']) + 30; 
                        $clientOptions = ['clientOptions' => [
                            'legend' => ['display' => false],
                            'responsive' => true,
                            'scales' => [                               
                                'xAxes' => [[
                                    'ticks' => [
                                        'stepSize' => $stepSize,                                        
                                        'min' => 0,
                                        'autoSkip' => false,
                                        'max' => $max_value
                                    ],
                                    'scaleLabel' => [
                                        'display' => true,
                                        'labelString' => $chart_title,                                        
                                    ],
                                ]],
                            ]
                        ]]; 
                    }
                                        
                    if($selected_chart_type == LeaveStatistic::CHARTTYPE_BAR){
                        $clientOptions = ['clientOptions' => [
                            'legend' => ['display' => false, 'position' => 'bottom'],
                            'responsive' => true,
                            'scales' => [
                                'xAxes' => [[
                                    'ticks' => [
                                        'stepSize' => $stepSize,
                                        'min' => 0,
                                        'autoSkip' => false,
                                        'minRotation' => $minrotation,                                        
                                        'max' => $max_value,
                                        'fontSize' => $fontsize,
                                    ]
                                ]],
                                'yAxes' => [[
                                    'ticks' => [
                                        'stepSize' => $stepSize,
                                        'min' => 0,
                                        'maxRotation' => 90,
                                        'autoSkip' => true,
                                        'max' => $max_value
                                    ],
                                    'scaleLabel' => [
                                        'display' => true,
                                        'labelString' => 'Πλήθος αδειών',
                                    ],
                                ]],
                            ]
                            ]]; 
                    }
                    else $clientOptions['clientOptions']['legend'] = ['position' => 'bottom'];
                    
                    $clientOptions['clientOptions']['title'] = ['display' => true, 'text' => $chart_title];                    
                    
                    $colors = ['#f6e58d', '#e056fd', '#686de0', '#30336b', '#95afc0', '#22a6b3', '#be2edd', '#4834d4', '#130f40', '#130f40',
                               '#ffbe76', '#ff7979', '#badc58', '#c7ecee', '#f9ca24', '#f0932b', '#eb4d4b', '#6ab04c', '#7ed6df', '#535c68']; 
                    $more_colors = count($result_data['LABELS']) - count($colors);            
                    for(; $more_colors > 0; $more_colors--){
                        array_push($colors, 'rgb(' . rand(0, 255) .',' . rand(0, 255) . ',' . rand(0, 255) . ')');
                    }
                    $colors = array_slice($colors, 0, count($result_data['LABELS']));
                    
                    echo ChartJs::widget(['type' => $selected_chart_type,
                                          'options' => ['height' => $height],
                                          'data' => [   'labels' => $result_data['LABELS'],
                                                        'datasets' => [['label' => Yii::t('app', "Πλήθος Μετακινήσεων"), 
                                                                     'backgroundColor' => $colors,
                                                                     'data' => $result_data['TRANSPORTS_COUNT']]]],                                          
                                                        'clientOptions' => $clientOptions['clientOptions']                                          
                                     ]);
            ?> 
        </div>
	</div>
</div>
<hr />
<div id="tableData" class="container">
	<div class="table-responsive col-lg-12">
		<table class="table table-bordered table-striped table-hover table-condensed">
			<thead>
				<tr><th colspan="2" class='text-center info'><?= $chart_title; ?></th></tr>
				<tr><th>&nbsp;</th><th class="text-center">Πλήθος Μετακινήσεων</th></tr>
			</thead>
			<tbody>
				<?php 
				    $num_of_data = count($result_data['LABELS']);
				    for ($i = 0; $i < $num_of_data; $i++){
				        echo "<tr><td>" . $result_data['LABELS'][$i] . "</td><td class='text-center'>" . $result_data['TRANSPORTS_COUNT'][$i] . "</td></tr>";
				    }
				?>
				<tr><td><strong>ΣΥΝΟΛΟ:</strong></td><td class='text-center'><strong><?= array_sum($result_data['TRANSPORTS_COUNT']); ?></strong></td></tr>				
			</tbody>
		</table>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="pull-right">
            	<form method="post" id="exportStatistic" action="leave-statistic/exportstatistic">
            		<button type="submit" class="btn btn-primary" onclick='chartjsToImage()'>
        				<?= Yii::t('app', 'Export to PDF'); ?>
    				</button>
            	</form>
    		</div>
        </div>
    </div>
</div>
