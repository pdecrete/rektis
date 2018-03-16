<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportProgramcategory */

$this->title = Yii::t('app', 'Create Schtransport Programcategory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schtransport Programcategories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schtransport-programcategory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
