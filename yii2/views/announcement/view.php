<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Announcement;

/* @var $this yii\web\View */
/* @var $model app\models\Announcement */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Announcements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="announcement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->deleted == Announcement::ANNOUNCEMENT_ACTIVE) : ?>
            <?=
            Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])

            ?>
        <?php else: ?>
            <?=
            Html::a(Yii::t('app', 'Restore'), ['restore', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to restore this item?'),
                    'method' => 'post',
                ],
            ])

            ?>
        <?php endif; ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'body:html',
            'created_at',
            'updated_at',
            'deleted',
        ],
    ])

    ?>

</div>
