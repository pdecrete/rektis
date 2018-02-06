<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\SubstituteTeacherFile */

$this->title = Yii::t('substituteteacher', 'Update File: ') . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Substitute Teacher Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('substituteteacher', 'Update');
?>
<div class="substitute-teacher-file-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
