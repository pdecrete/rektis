<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecredit */

$this->title = Yii::t('app', 'Update RCΝ credits');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Kaecredits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

//echo "<pre>"; print_r($kaes); echo "</pre>";
//echo "<pre>"; print_r($kaeCredits); echo "</pre>";
//die();

?>
<div class="finance-kaecredit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_editform', ['model' => $model, 'kaetitles' => $kaetitles]); ?>

</div>
