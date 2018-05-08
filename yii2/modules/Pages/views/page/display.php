<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="page-display">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="text-muted">Τελευταία ενημέρωση <span class="label label-default"><?php echo $model->updated_at_str; ?></span></p>

    <?php echo $model->content; ?>

</div>
