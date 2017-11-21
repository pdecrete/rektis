<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = 'Διαχείριση Οικονομικού Έτους';
$this->params['breadcrumbs'][] = $this->title;

?>

<h1>Διαχείριση Οικονομικού Έτους</h1>

<div class="body-content">

    <div class="row">
		<div class="col-lg-4">
            <h3>Αρχικοποίηση Έτους</h3>
            <p>Εκκίνηση νέου έτους με απόδοση πίστωσης σε αυτό.</p>
            <p><?= Html::a('Αρχικοποίηση Έτους', Url::to(['/finance/finance-year']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
        <div class="col-lg-4">
            <h3>Πιστώσεις ΚΑΕ</h3>
            <p>Απόδοση Πιστώσεων στους ΚΑΕ του τρέχοντος έτους</p>
            <p><?= Html::a('Καθορισμός Πιστώσεων', Url::to(['/finance/finance-supplier']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
        <div class="col-lg-4">
            <h3>Κρατήσεις</h3>
            <p>Διαχείριση κρατήσεων (δημιουργία νέας κράτησης, ενημέρωση, διαγραφή)</p>
        </div> 
    </div>
</div>
