<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Position */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Positions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="position-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('substituteteacher', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])

        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'operation_id',
            'specialisation_id',
            'prefecture_id',
            [
                'attribute' => 'position_has_type',
                'value' => $model->position_has_type_label
            ],
            'teachers_count',
            'hours_count',
            'covered_teachers_count',
            'covered_hours_count',
            'whole_teacher_hours',
            'created_at',
            'updated_at',
        ],
    ])

    ?>

</div>
