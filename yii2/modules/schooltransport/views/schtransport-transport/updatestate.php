<?php

use app\modules\schooltransport\Module;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportTransport */

$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'View Transportations'), 'url' => ['/schooltransport/schtransport-transport']];
$this->title = Module::t('modules/schooltransport/app', 'Update Transportation Approval State');
$this->params['breadcrumbs'][] = $this->title;

//echo "<pre>"; print_r($schools); echo "</pre>";die();
?>
<div class="schtransport-transport-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_transportstateform', [  'transportstate_model' => $transportstate_model,
                                                'state_name' => $state_name]);?>

</div>
