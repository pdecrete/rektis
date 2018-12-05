<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\Disposal */

$this->title = DisposalModule::t('modules/disposal/app', 'Create Teacher Disposal');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Disposals for Approval'), 'url' => ['/disposal/disposal/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teacher_model' => $teacher_model,
        'localdirdecision_model' => $localdirdecision_model,
        'schools' => $schools,
        'disposal_hours' => $disposal_hours,
        'disposal_days' => $disposal_days,
        'specialisations' => $specialisations,
        'disposal_reasons' => $disposal_reasons,
        'disposal_workobjs' => $disposal_workobjs,
        'directorates' => $directorates,
        'teacher_disabled' => false,
        'ldrdec_disabled' => false
    ]) ?>

</div>
