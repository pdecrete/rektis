<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\PlacementPreference */

$this->title = Yii::t('substituteteacher', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Placement Preferences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('substituteteacher', 'Update');
?>
<div class="placement-preference-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
