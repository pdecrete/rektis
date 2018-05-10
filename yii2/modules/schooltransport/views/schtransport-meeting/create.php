<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportMeeting */

$this->title = Yii::t('app', 'Create Schtransport Meeting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schtransport Meetings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schtransport-meeting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
