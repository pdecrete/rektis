<?php

use app\modules\schooltransport\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportState */

$this->title = Module::t('modules/schooltransport/app', 'Update State Title');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'View States'), 'url' => ['/schooltransport/schtransport-state']];

$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="schtransport-state-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
