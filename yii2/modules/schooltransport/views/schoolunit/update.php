<?php

use app\modules\schooltransport\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\Schoolunit */
$this->title = Module::t('modules/schooltransport/app', 'Update school unit');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="schoolunit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'directorates' => $directorates
    ]) ?>

</div>
