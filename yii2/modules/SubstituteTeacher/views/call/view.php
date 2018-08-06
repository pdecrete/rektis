<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Call */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Calls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="call-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('substituteteacher', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to delete this call?'),
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
            'year',
            'description:ntext',
            [
                'attribute' => 'application_start',
                'value' => function ($m) {
                    return \Yii::$app->formatter->asDate($m->application_start, 'php:l d F Y');
                }
            ],
            [
                'attribute' => 'application_end',
                'value' => function ($m) {
                    return \Yii::$app->formatter->asDate($m->application_end, 'php:l d F Y');
                }
            ],
            [
                'attribute' => '',
                'label' => Yii::t('substituteteacher', 'Teachers to appoint'),
                'value' => $model->callTeacherSpecialisations ? implode(
                    '<br>',
                        array_map(function ($m) {
                            return "{$m->teachers}, {$m->specialisation->label}";
                        }, $model->callTeacherSpecialisations)
                    ) : null
                ,
                'format' => 'html'
            ],
            [
                'attribute' => '',
                'label' => Yii::t('substituteteacher', 'Teachers to call'),
                'value' => $model->callTeacherSpecialisations ? implode(
                    '<br>',
                        array_map(function ($m) {
                            return "{$m->teachers_call}, {$m->specialisation->label}";
                        }, $model->callTeacherSpecialisations)
                    ) : null
                ,
                'format' => 'html'
            ],
            'created_at',
            'updated_at',
        ],
    ])

    ?>

</div>
