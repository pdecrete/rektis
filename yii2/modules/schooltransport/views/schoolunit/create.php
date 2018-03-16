<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\Schoolunit */

$this->title = Yii::t('app', 'Create Schoolunit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schoolunits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schoolunit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
