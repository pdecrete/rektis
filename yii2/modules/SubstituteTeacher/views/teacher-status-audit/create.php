<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\TeacherStatusAudit */

$this->title = Yii::t('substituteteacher', 'Create Teacher Status Audit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Teacher Status Audits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-status-audit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
