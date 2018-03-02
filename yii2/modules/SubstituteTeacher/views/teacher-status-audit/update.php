<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\TeacherStatusAudit */

$this->title = Yii::t('substituteteacher', 'Update {modelClass}: ', [
    'modelClass' => 'Teacher Status Audit',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Teacher Status Audits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('substituteteacher', 'Update');
?>
<div class="teacher-status-audit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
