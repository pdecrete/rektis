<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\TeacherRegistry */

$this->title = Yii::t('substituteteacher', 'Create Teacher Registry');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Teacher Registries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-registry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
