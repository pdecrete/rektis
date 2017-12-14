<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecredit */

$this->title = Yii::t('app', 'Create Finance Kaecredit');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Finance Kaecredits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//echo "<pre>"; print_r($model); echo "</pre>";die();
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-kaecredit-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_editform', ['model' => $model, 'kaetitles' => $kaetitles]); ?>

</div>