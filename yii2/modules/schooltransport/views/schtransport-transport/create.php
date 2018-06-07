<?php

use app\modules\schooltransport\Module;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportTransport */

$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'Transportations Approvals'), 'url' => ['/schooltransport/schtransport-transport']];
$this->title = Module::t('modules/schooltransport/app', 'Create Transportation');
$this->params['breadcrumbs'][] = $this->title . " (" . $program_title . ")";

//echo "<pre>"; print_r($schools); echo "</pre>";die();
?>
<div class="schtransport-transport-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model,
                                'meeting_model' => $meeting_model,
                                'program_model' => $program_model,
                                'schools' => $schools,
                                'typeahead_data' => $typeahead_data,
                                'programcateg_id' => $programcateg_id,
                                'sep' => $sep,
                                'program_alias' => $program_alias,
                                'disabled' => false
    ]);?>

</div>
