<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportState */

$this->title = Yii::t('app', 'Create Schtransport State');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schtransport States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schtransport-state-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
