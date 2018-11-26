<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\disposal\DisposalModule;

/* @var $this yii\web\View */
$this->title = DisposalModule::t('modules/disposal/app', 'Help');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$collapse_in = [1 => "", 2 => "", 3 => ""];
if (array_key_exists($helpId, $collapse_in)) {
    $collapse_in[$helpId] = 'in';
}

?>

<div class="body-content">
  <div class="panel-group" id="accordion">
  	
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?= $app_help['title']?></a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse <?php echo $collapse_in[1];?>">
        <div class="panel-body">
        	<?= $app_help['content']?>
		</div>
      </div>
    </div>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><?= $approval_help['title']?></a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse <?php echo $collapse_in[2];?>">
        <div class="panel-body">
        	<?= $approval_help['content']?>        	
    	    <div class="form-group text-right">
				<?= Html::a(Yii::t('app', 'Update'), ['/Pages/page/update', 'id' => $approval_help['id']], ['class' => 'btn btn-primary']) ?>
			</div>
			<div><em>Τελευταία ενημέρωση: <?= date("d-m-Y", $approval_help['updated_at'])?></em></div>
		</div>
      </div>
    </div>
   
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Νομοθεσία σχετική με τις σχολικές μετακινήσεις.</a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse <?php echo $collapse_in[3];?>">
        <div class="panel-body">
        	<ul>
    			<li><a href="<?php echo Url::to(['legislation', 'fileId' => 1]);?>">ΦΕΚ 1340</a> (<i>16/10/2002</i>)</li>
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 2]);?>">ΦΕΚ 26 (Νόμος 3528/2007)</a> (<i>09/02/2007</i>)</li>
    			<li><a href="<?php echo Url::to(['legislation', 'fileId' => 3]);?>">ΦΕΚ 235</a> (<i>01/11/2013</i>)</li>								
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 4]);?>">ΦΕΚ 83</a> (<i>11/05/2016</i>)</i></li>		
			</ul>
        <p><em>Τελευταία Ενημέρωση: 20-08-2018</em></p>
        </div>
      </div>
    </div>

</div>
</div>
