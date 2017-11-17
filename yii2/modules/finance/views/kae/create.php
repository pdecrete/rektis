<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\KAE */

$this->title = Yii::t('app', 'Create Kae');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kaes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kae-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
