<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $model->surname . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            /*[
              'label' => 'A/A',
              'attribute' => 'id',
            ],*/
            [
              'label' => Yii::t('app', 'Status'),
              'attribute' => 'status0.name',
            ],
            'name',
            'surname',
            'fathersname',
            'mothersname',
            'tax_identification_number',
            'email:email',
            'telephone',
            'mobile',
            'address',
            'identity_number',
            'social_security_number',
            [
              'label' => Yii::t('app', 'Specialisation'),
              'attribute' => 'specialisation0.code',
            ],
            'identification_number',
            'appointment_fek',
            [
              'label' => Yii::t('app', 'Appointment Date'),
              'attribute' => function ($data) { return \Yii::$app->formatter->asDate($data['appointment_date']); }
            ],
            [
              'label' => Yii::t('app', 'Service Organic'),
              'attribute' => 'serviceOrganic.name'
            ],
            [
              'label' => Yii::t('app', 'Service Serve'),
              'attribute' => 'serviceServe.name'
            ],
            [
              'label' => Yii::t('app', 'Position'),
              'attribute' => 'position0.name'
            ],
            [
              'label' => Yii::t('app', 'Rank'),
              'attribute' => 'rank0'
            ],
            //'rank0',
            [
              'label' => Yii::t('app', 'Rank Date'),
              'attribute' => function ($data) { return \Yii::$app->formatter->asDate($data['rank_date']); }
            ],
            'pay_scale',
            [
              'label' => Yii::t('app', 'Pay Scale Date'),
              'attribute' => function ($data) { return \Yii::$app->formatter->asDate($data['pay_scale_date']); }
            ],
            'service_adoption',
            [
              'label' => Yii::t('app', 'Service Adoption Date'),
              'attribute' => function ($data) { return \Yii::$app->formatter->asDate($data['service_adoption_date']); }
            ],
            'master_degree',
            'doctorate_degree',
            'work_experience',
            'comments:ntext',
            [
              'label' => Yii::t('app', 'Created At'),
              'attribute' => function ($data) { return \Yii::$app->formatter->asDateTime($data['create_ts']); }
            ],
            [
              'label' => Yii::t('app', 'Updated At'),
              'attribute' => function ($data) { return \Yii::$app->formatter->asDateTime($data['update_ts']); }
            ]
        ],
    ]) ?>

</div>
