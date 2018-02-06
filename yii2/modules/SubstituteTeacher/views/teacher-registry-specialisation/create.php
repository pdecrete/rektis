<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\TeacherRegistrySpecialisation */

$this->title = Yii::t('substituteteacher', 'Create Teacher Registry Specialisation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Teacher Registry Specialisations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-registry-specialisation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
