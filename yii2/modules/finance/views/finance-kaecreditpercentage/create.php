<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecreditpercentage */

$this->title = Yii::t('app', 'Create Finance Kaecreditpercentage');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Kaecreditpercentages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-kaecreditpercentage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
