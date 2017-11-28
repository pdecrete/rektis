<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Επιβεβαίωση κλειδώματος οικονομικού έτους' . $id;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="jumbotron">
    <h1>Επιβεβαίωση κλειδώματος οικονομικού έτους</h1>
    <p>Είστε βέβαιοι για το κλείδωμα του οικονομικού έτους;</p>
    <p><?= Html::a('Κλείδωμα', ['/finance/finance-year/lock' ], ['class' => 'btn btn-danger', 'data-method' => 'POST', 'data-confirm' => 'Το κλείδωμα του οικονομικού έτους είναι μη αναστρέψιμη ενέργεια. Είστε απόλυτα βέβαιοι;']) ?></p>
    <p><?= Html::a('Άκυρο', ['/finance/finance-year'], ['class' => 'btn btn-default']) ?></p>
</div>
