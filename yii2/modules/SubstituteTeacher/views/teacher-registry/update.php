<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\TeacherRegistry */

$this->title = Yii::t('substituteteacher', 'Update {modelClass}: ', [
    'modelClass' => 'Teacher Registry',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Teacher Registries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('substituteteacher', 'Update');
?>
<div class="teacher-registry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
