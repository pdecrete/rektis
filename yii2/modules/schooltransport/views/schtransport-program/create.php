<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportProgram */

$this->title = Yii::t('app', 'Create Schtransport Program');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schtransport Programs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schtransport-program-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
