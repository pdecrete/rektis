<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Call */

$this->title = Yii::t('substituteteacher', 'Create Call');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Calls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="call-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', compact('model', 'modelsCallTeacherSpecialisation')) ?>

</div>
