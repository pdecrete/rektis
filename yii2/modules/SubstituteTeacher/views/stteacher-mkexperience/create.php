<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\StteacherMkexperience */

$this->title = Yii::t('substituteteacher', 'Create Stteacher Mkexperience');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Stteacher Mkexperiences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stteacher-mkexperience-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
