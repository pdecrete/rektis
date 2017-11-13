<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Announcement */

$this->title = Yii::t('app', 'Create Announcement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Announcements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="announcement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])

    ?>

</div>
