<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'status',
            'name',
            'surname',
            'fathersname',
            'mothersname',
            'tax_identification_number',
            'email:email',
            'telephone',
            'address',
            'identity_number',
            'social_security_number',
            'specialisation',
            'identification_number',
            'appointment_fek',
            'appointment_date',
            'service_organic',
            'service_serve',
            'position',
            'rank',
            'rank_date',
            'pay_scale',
            'pay_scale_date',
            'service_adoption',
            'service_adoption_date',
            'master_degree',
            'doctorate_degree',
            'work_experience',
            'comments:ntext',
            'create_ts',
            'update_ts',
        ],
    ]) ?>

</div>
