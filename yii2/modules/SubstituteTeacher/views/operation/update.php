<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Operation */

$this->title = Yii::t('substituteteacher', 'Update Operation: ') . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Operations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('substituteteacher', 'Update');

?>
<div class="operation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])

    ?>

</div>