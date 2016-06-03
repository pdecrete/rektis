<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = 'Προσθήκη εργαζομένου';
$this->params['breadcrumbs'][] = ['label' => 'Εργαζόμενοι', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-create">

    <h1><?= Html::encode($this->title) ?></h1>
     <?php
         // Convert to uppercase using jQuery
         $myJs = "$('input[type=text]').change(function() {\$(this).val($(this).val().toUpperCase());}); ";
         $this->registerJs($myJs, $this::POS_END);
     ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
