<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Operation */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Operations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="operation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('substituteteacher', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to delete this operation?'),
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
            'year',
            'title',
            [
                'attribute' => 'specialisation_labels',
                'format' => 'html'
            ],
            'description',
            [
                'attribute' => 'logo',
                'value' => '@web/images/' . $model->logo,
                'format' => ['image', ['class' => 'img-responsive']]
            ],
            'created_at',
            'updated_at',
        ],
    ])

    ?>

</div>
