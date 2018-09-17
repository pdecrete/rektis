<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalLocaldirdecision */

$this->title = Yii::t('app/modules/disposal/', 'Create Disposal Localdirdecision');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/disposal/', 'Disposal Localdirdecisions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-localdirdecision-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
