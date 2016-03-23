<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Ενημέρωση στοιχείων χρήστη: ' . $model->name . ' ' . $model->surname . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Χρήστες', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Ενημέρωση';
?>
<div class="user-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
