<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalLocaldirdecision */

$this->title = DisposalModule::t('modules/disposal/app', 'Update Local Directorate Decision');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Local Directorate Decisions'), 'url' => ['/disposal/disposal-localdirdecision']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-localdirdecision-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'directorates' => $directorates
    ]) ?>

</div>