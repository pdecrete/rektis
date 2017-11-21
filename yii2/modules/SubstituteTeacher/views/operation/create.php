<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Operation */

$this->title = Yii::t('substituteteacher', 'Create Operation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Operations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="operation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])

    ?>

</div>
