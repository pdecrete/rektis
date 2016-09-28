<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Specialisation */

$this->title = Yii::t('app', 'Create Specialisation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Specialisations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="specialisation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
