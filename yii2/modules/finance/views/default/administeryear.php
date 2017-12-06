<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use app\modules\finance\Module;

/* @var $this yii\web\View */
$this->params['breadcrumbs'][] = ['label' =>  Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = 'Διαχείριση Οικονομικού Έτους';
$this->params['breadcrumbs'][] = $this->title;

?>

<h1>Διαχείριση Οικονομικού Έτους</h1>

<div class="body-content">

    <div class="row">
		<div class="col-lg-4">
            <h3>Ρυθμίσεις Έτους</h3>
            <p>Δημιουργία/Επεξεργασία/Διαγραφή/Κλείδωμα/Ορισμός ως τρέχον</p>
            <p><?= Html::a('Ρυθμίσεις Έτους', Url::to(['/finance/finance-year']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
        <div class="col-lg-4">
            <h3>Πιστώσεις ΚΑΕ</h3>
            <p>Καθορισμός πιστώσεων στους ΚΑΕ του <strong>τρέχοντος</strong> έτους</p>
            <p><?= Html::a('Καθορισμός Πιστώσεων', Url::to(['/finance/finance-kaecredit']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
		<div class="col-lg-4">
            <h3>Ποσοστά διάθεσης πιστώσεων ΚΑΕ</h3>
            <p>Καθορισμός ποσοστών διάθεσης των πιστώσεων των ΚΑΕ του τρέχοντος έτους.</p>
            <p><?= Html::a('Ρυθμίσεις KAE', Url::to(['/finance/finance-kaecreditpercentage']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
    </div>
	<div class="row">
		<div class="col-lg-4">
            <h3>Διαχείριση ΚΑΕ</h3>
            <p>Δημιουργία/Επεξεργασία/Διαγραφή ΚΑΕ</p>
            <p><?= Html::a('Ρυθμίσεις KAE', Url::to(['/finance/finance-kae']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
	</div>
</div>
