<?php

use app\modules\schooltransport\Module;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->title = Module::t('modules/schooltransport/app', 'Help');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="body-content">
  <div class="panel-group" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Βοήθεια σχετικά με την εφαρμογή των σχολικών μετακινήσεων.</a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse">
        <div class="panel-body">
		</div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Βοήθεια σχετικά με τη δημιουργία έγκρισης σχολικής μετακίνησης.</a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse">
        <div class="panel-body">
		</div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Νομοθεσία σχετική με τις σχολικές μετακινήσεις.</a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse">
        <div class="panel-body">
        <p>
		</p>
        <p><em>Τελευταία Ενημέρωση: 26-04-2018</em></p>
        </div>
      </div>
    </div>

</div>
</div>
