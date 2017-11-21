<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Διαχείριση Δαπανών';
$this->params['breadcrumbs'][] = $this->title;

?>

<h1>Διαχείριση Δαπανών</h1>

<div class="body-content">

    <div class="row">
		<div class="col-lg-4">
            <h3>Διαχείριση Οικονομικού Έτους</h3>
            <p>Λειτουργίες διαχείρισης οικονομικού έτους (νέο έτος, κατανομή πιστώσεων κτλ.)</p>
            <p><?= Html::a('Προβολή επιλογών', Url::to(['/finance/default/administeryear']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
        <div class="col-lg-4">
            <h3>Προμηθευτές</h3>
            <p>Διαχείριση μητρώου προμηθευτών (δημιουργία νέου προμηθευτή, ενημέρωση στοιχείων κτλ.)</p>
            <p><?= Html::a('Προβολή', Url::to(['/finance/finance-supplier']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
        <div class="col-lg-4">
            <h3>Κρατήσεις</h3>
            <p>Διαχείριση κρατήσεων (δημιουργία νέας κράτησης, ενημέρωση, διαγραφή)</p>
        </div> 
    </div>

    <div class="row">
		<div class="col-lg-4">
            <h3>Δαπάνες</h3>
            <p>Λειτουργίες διαχείρισης δαπανών (νέα δαπάνη, ενημέρωση στοιχείων δαπάνης, διαγραφή κτλ.)</p>
        </div>
        <div class="col-lg-4">
            <h3>Τιμολόγια</h3>
            <p>Διαχείριση τιμολογίων (δημιουργία νέου προμηθευτή, ενημέρωση στοιχείων κτλ.)</p>
        </div>
        <div class="col-lg-4">
            <h3>Αναφορές</h3>
            <p>Αναφορές δαπανών, τιμολογίων, πιστώσεων κτλ.</p>
        </div> 
    </div>
    <div class="row">
		<div class="col-lg-4">
            <h3>Παραμετροποίηση</h3>
            <p>Αλλαγή παραμέτρων συστήματος (ΚΑΕ, Κρατήσεις, ΔΟΥ)</p>
            <p><?= Html::a('Προβολή επιλογών', Url::to(['/finance/default/parameterize']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
    </div>
</div>
