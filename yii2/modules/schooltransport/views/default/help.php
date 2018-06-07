<?php

use app\modules\Pages\models\Page;
use app\modules\schooltransport\Module;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->title = Module::t('modules/schooltransport/app', 'Help');
$this->params['breadcrumbs'][] = $this->title;

$collapse_in = [1 => "", 2 => "", 3 => ""];
if(array_key_exists($helpId, $collapse_in)){
    $collapse_in[$helpId] = 'in';
}

$app_help = Page::findOne(['identity' => 'schtransport_apphelp']);
if(is_null($app_help)){
    $app_help['title'] = 'Η σελίδα βοήθειας με λεκτικό αναγνωριστικό <em>"schtransport_apphelp"</em> δεν βρέθηκε.';
    $app_help['content'] = '';
}
$approval_help = Page::findOne(['identity' => 'schtransport_approvalhelp']);
if(is_null($approval_help)){
    $approval_help['title'] = 'Η σελίδα βοήθειας με λεκτικό αναγνωριστικό <em>"schtransport_approvalhelp"</em> δεν βρέθηκε.';
    $approval_help['content'] = '';
    $approval_help['id'] = -1;
    $approval_help['updated_at'] = -1;
}
$legislation_help = Page::findOne(['identity' => 'schtransport_legislationhelp']);
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
			<div><em>Τελευταία ενημέρωση: <?= date("d-m-Y",$approval_help['updated_at'])?></em></div>
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
    			<li><a href="<?php echo Url::to(['legislation', 'fileId' => 2]);?>">ΦΕΚ 2769 / Εκδρομές-Μετακινήσεις μαθητών Δημοσίων και Ιδιωτικών σχολείων Δευτεροβάθμιας Εκπαίδευσης εντός και εκτός της χώρας.</a> (<i>02/12/2011</i>)</li>
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 1]);?>">Εκδρομές-μετακινήσεις μαθητών Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσεις στο εξωτερικό.</a> (<i>Έγγραφο ΥΠΕΠΘ: Φ10/4218/Δ2 - 11/01/2017</i>)</li>
    			<li><a href="<?php echo Url::to(['legislation', 'fileId' => 3]);?>">ΦΕΚ 681 / Εκδρομές-Μετακινήσεις μαθητών Δημοσίων και Ιδιωτικών σχολείων Δευτεροβάθμιας Εκπαίδευσης εντός και εκτός της χώρας.</a> (<i>06/03/2017</i>)</li>				
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 6]);?>">Μετακινήσεις μαθητών Δημοτικών Σχολείων στη Βουλή των Ελλήνων - Συμμετοχή στο Εργαστήρι Δημοκρατίας.</a> (<i>Έγγραφο ΥΠΕΠΘ: Φ.12/ΦΜ/48140/Δ1 - 21/03/2017</i>)</li>				
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 4]);?>">ΦΕΚ 109 / Προεδρικό Διάταγμα υπ' αριθμ. 79.</a> <i>(01/08/2017)</i></li>
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 5]);?>">Μετακινήσεις μαθητών Δημοτικών Σχολείων στη Βουλή των Ελλήνων - Συμμετοχή στο Εργαστήρι Δημοκρατίας.</a> (<i>Έγγραφο ΥΠΕΠΘ: Φ.12/ΦΜ/53243/Δ1 - 02/04/2018</i>)</li>		
			</ul>
        <p><em>Τελευταία Ενημέρωση: 05-05-2018</em></p>
        </div>
      </div>
    </div>

</div>
</div>
