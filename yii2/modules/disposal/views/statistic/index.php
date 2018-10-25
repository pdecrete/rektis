<?php
/* @var $this yii\web\View */
use dosamigos\chartjs\ChartJs;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\modules\disposal\models\Statistic;

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

$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->title = Module::t('modules/schooltransport/app', 'Statistics');
$this->params['breadcrumbs'][] = $this->title;

$current_startyear = Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", date("Y-m-d")));
//echo "<pre>"; print_r($model); echo "</pre>";die();
?>

<div class="text-right">
        <div class="btn-group">
      		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
      			<?= Module::t('modules/schooltransport/app', 'Export transportations to Excel file.'); ?> <span class="caret"></span>
  			</button>
  			<ul class="dropdown-menu" role="menu">
    			<li><a href="<?= Url::to(['statistic/exportexcel', 'period' => $current_startyear]);?>">
					<?= Module::t('modules/schooltransport/app', 'Current school year'); ?></a>
				</li>
    			<li><a href="<?= Url::to(['statistic/exportexcel', 'period' => ($current_startyear-1)]);?>">
    				<?= Module::t('modules/schooltransport/app', 'Previous school year'); ?>
    				</a>
				</li>
    			<li><a href="<?= Url::to(['statistic/exportexcel', 'period' => -1]);?>">
					<?= Module::t('modules/schooltransport/app', 'All years'); ?>
					</a>
				</li>
  			</ul>
    	</div>
</div>
<?php ?>
<h1>Επιλογή Παραμέτρων</h1>
<div class="well container-fluid">
<?php $form = ActiveForm::begin(); ?>
	<div class="row">
    	<div class="col-lg-6">
            <?= $form->field($model, 'statistic_schoolyear')->widget(Select2::classname(), [
                'data' => $school_years,
                'options' => ['multiple' => true, 'placeholder' => Module::t('modules/schooltransport/app', 'Select school year...')],
                ])->label('Σχολικό Έτος'); 
            ?>
    	</div>
    	<div class="col-lg-6">
	        <?= $form->field($model, 'statistic_prefecture')->widget(Select2::classname(), [
            'data' => $prefectures,
                'options' => ['placeholder' => Module::t('modules/schooltransport/app', 'Select prefecture...')],
                ])->label('Νομός'); 
	        ?>
    	</div>
	</div>
	<div class="row">
    	<div class="col-lg-6">
	        <?= $form->field($model, 'statistic_country')->widget(Select2::classname(), [
                'data' => $countries,
                'options' => ['placeholder' => Module::t('modules/schooltransport/app', 'Select country...')],
                ])->label('Χώρα'); 
            ?>
    	</div>
    	<div class="col-lg-6">
            <?= $form->field($model, 'statistic_educationlevel')->widget(Select2::classname(), [
                'data' => $education_levels,
                'options' => ['placeholder' => Module::t('modules/schooltransport/app', 'Select education level...')],
                ])->label('Βαθμίδα'); 
            ?>
    	</div>
	</div>
	<div class="row">
    	<div class="col-lg-6">
            <?= $form->field($model, 'statistic_program')->widget(Select2::classname(), [
                'data' => $program_categs,
                'options' => ['placeholder' => Module::t('modules/schooltransport/app', 'Select program category...')],
                ])->label('Κατηγορία Πρoγράμματος'); 
            ?>    	
    	</div>
    	<div class="col-lg-6">
            <?= $form->field($model, 'statistic_groupby')->widget(Select2::classname(), [
                'data' => $groupby_options,
                'options' => ['placeholder' => Module::t('modules/schooltransport/app', 'Select grouping method...')],
                ])->label('Ομαδοποίηση'); 
            ?>
    	</div>
	</div>	    
  	<div class="row">
    	<div class="col-lg-6">
            <?= $form->field($model, 'statistic_charttype')->widget(Select2::classname(), [
                'data' => $chart_types,
                'options' => ['placeholder' => Module::t('modules/schooltransport/app', 'Select chart type...')],
                ])->label('Τύπος Γραφήματος'); 
            ?>
    	</div>	
  	</div>
	<div class="row pull-right">    
        <div class="form-group col-lg-6">  
            <?= Html::submitButton(Module::t('modules/schooltransport/app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
</div>

<div id="canvasChart" class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?php   
                    $height = 100;
                    $clientOptions['clientOptions'] = '';
                    if($selected_chart_type == Statistic::CHARTTYPE_HORIZONTALBAR){
                        $height = 15*count($result_data['TRANSPORTS_COUNT']) + 30;
                        $clientOptions = ['clientOptions' => [
                            'legend' => ['display' => false],
                            'responsive' => true,
                            'scales' => [                               
                                'xAxes' => [[
                                    'ticks' => [
                                        'min' => 0,
                                        'stepSize' => 1,
                                        'max' => max($result_data['TRANSPORTS_COUNT']) + 1
                                    ],
                                    'scaleLabel' => [
                                        'display' => true,
                                        'labelString' => 'Πλήθος Μετακινήσεων',
                                    ],
                                ]],
                            ]
                        ]]; 
                    }
                                        
                    if($selected_chart_type == Statistic::CHARTTYPE_BAR){
                        $clientOptions = ['clientOptions' => [
                            'legend' => ['display' => false, 'position' => 'bottom'],
                            'responsive' => true,
                            'scales' => [
                                'yAxes' => [[
                                    'ticks' => [
                                        'min' => 0,
                                        'stepSize' => 1,
                                        'max' => max($result_data['TRANSPORTS_COUNT']) + 2
                                    ],
                                    'scaleLabel' => [
                                        'display' => true,
                                        'labelString' => 'Πλήθος Μετακινήσεων',
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
                                          'options' => ['height' => $height, 'animation' => ['onAnimationComplete' => new \yii\web\JsExpression('alert("Hallo");')]],
                                          'data' => [   'labels' => $result_data['LABELS'],
                                                        'datasets' => [['label' => Module::t('modules/schooltransport/app', "Πλήθος Μετακινήσεων"),
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
				<tr><th>&nbsp;</th><th class="text-center">Πλήθος Σχολικών Μετακινήσεων</th></tr>
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
            	<form method="post" id="exportStatistic" action="exportstatistic">
            		<button type="submit" class="btn btn-primary" onclick='chartjsToImage()'>
        				<?= Module::t('modules/schooltransport/app', 'Export to PDF'); ?>
    				</button>
            	</form>
    		</div>
        </div>
    </div>
</div>
