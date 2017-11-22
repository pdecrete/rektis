<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Prefecture */

$this->title = Yii::t('app', 'Create Prefecture');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Prefectures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prefecture-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
