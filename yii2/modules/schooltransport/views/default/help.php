<?php

use app\modules\schooltransport\Module;
use yii\helpers\Url;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->title = Module::t('modules/schooltransport/app', 'Help');
$this->params['breadcrumbs'][] = $this->title;

$collapse_in = [1 => "", 2 => "", 3 => ""];
if(array_key_exists($helpId, $collapse_in)){
    $collapse_in[$helpId] = 'in';
}
?>

<div class="body-content">
  <div class="panel-group" id="accordion">
  	
    <div class="panel panel-default">
      <a id="schtransportsapp_help"></a>
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Βοήθεια σχετικά με την εφαρμογή των σχολικών μετακινήσεων.</a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse <?php echo $collapse_in[1];?>">
        <div class="panel-body">
            <h3>1. Δημιουργία Έγκρισης Μετακίνησης</h3>
            <p></p>
            <hr />
            <h3>2. Παράμετροι</h3>
            <h4>2.1. Σχολικές Μονάδες</h4>
            <p></p>
            <h4>2.2. Καταστάσεις Εγκρίσεων</h4>
            <p></p>
            <hr />
            <h3>3. Βοήθεια</h3>
            <h4>3.1. Βοήθεια εφαρμογής σχολικών μετακινήσεων</h4>
            <p></p>
            <h4>3.2. Διαδικασία έγκρισης σχολικής μετακίνησεις</h4>
            <p></p>
            <h4>3.3. Νομοθεσία σχολικών μετακινήσεων</h4>
            <p></p>            
            <hr />            
		</div>
      </div>
    </div>
    
    <div class="panel panel-default">
      <a id="schtransports_help"></a>
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Βοήθεια σχετικά με τη δημιουργία έγκρισης σχολικής μετακίνησης.</a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse <?php echo $collapse_in[2];?>">
        <div class="panel-body">
		</div>
      </div>
    </div>
   
    <div class="panel panel-default">
      <a id="legislation"></a>
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Νομοθεσία σχετική με τις σχολικές μετακινήσεις.</a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse <?php echo $collapse_in[3];?>">
        <div class="panel-body">
        	<ul>
    			<li><a href="<?php echo Url::to(['legislation', 'fileId' => 2]);?>">ΦΕΚ 2769 / Εκδρομές-Μετακινήσεις μαθητών Δημοσίων και Ιδιωτικών σχολείων Δευτεροβάθμιας Εκπαίδευσης εντός και εκτός της χώρας.</a> (<i>2/12/2011</i>)</li>
    			<li><a href="<?php echo Url::to(['legislation', 'fileId' => 3]);?>">ΦΕΚ 681 / Εκδρομές-Μετακινήσεις μαθητών Δημοσίων και Ιδιωτικών σχολείων Δευτεροβάθμιας Εκπαίδευσης εντός και εκτός της χώρας.</a> (<i>6/3/2017</i>)</li>
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 1]);?>">Εκδρομές-μετακινήσεις μαθητών Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσεις στο εξωτερικό.</a> (<i>Έγγραφο ΥΠΕΠΘ: Φ10/4218/Δ2 - 11/1/2017</i>)</li>
			</ul>
        <p><em>Τελευταία Ενημέρωση: 26-04-2018</em></p>
        </div>
      </div>
    </div>

</div>
</div>
