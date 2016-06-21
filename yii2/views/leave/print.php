<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\Leave */

$this->title = $model->information;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leaves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->information, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Print');
?>
<?php
if ($model->deleted) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger',
        ],
        'body' => Yii::t('app', 'This leave is marked as deleted.'),
    ]);
}
?>
<div class="leave-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Return to view'), ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Download'), ['download', 'id' => $model->id], [
            'class' => 'btn btn-success',
            'data' => [
                'method' => 'post',
            ],
        ])
        ?>
        <?=
        Html::a(Yii::t('app', 'Print again'), ['print', 'id' => $model->id], [
            'class' => 'btn btn-default',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to print this leave?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <?php if ($filename != null) : ?>
        <div class="alert alert-info" role="alert">Το αρχείο εκτύπωσης της άδειας είναι διαθέσιμο για μεταφόρτωση.</div>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">Το αρχείο εκτύπωσης της άδειας δεν είναι διαθέσιμο. Προσπαθήστε να το εκτυπώσετε ξανά.</div>
    <?php endif; ?>

</div>
