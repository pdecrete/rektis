<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\AuditLog */

$this->title = Yii::t('substituteteacher', 'Audit Log') . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Audit Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-log-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> ' . Yii::t('substituteteacher', 'Next newer'), ['next', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-right"></span> ' . Yii::t('substituteteacher', 'Next older'), ['previous', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'level',
                'value' => function ($m) {
                    return $m->level_label;
                },
                'format' => 'html'
            ],
            'category',
            'log_time:datetime',
            'prefix:ntext',
            [
                'attribute' => 'user',
                'value' => function ($model) {
                    return $model->user ? $model->user->username : null;
                }
            ],
            'message:ntext',
        ],
    ]) ?>

</div>