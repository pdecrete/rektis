<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\TeacherRegistry */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Teacher Registries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="teacher-registry-view">

        <h1>
            <?= Html::encode($this->title) ?>
        </h1>

        <p>
            <?= Html::a(Yii::t('substituteteacher', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('substituteteacher', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        </p>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'specialisation_ids',
                'value' => function ($m) {
                    return implode(', ', $m->specialisation_labels);
                }
            ],
            'gender_label',
            'surname',
            'firstname',
            'fathername',
            'mothername',
            'marital_status_label',
            'protected_children',
            'mobile_phone',
            'home_phone',
            'work_phone',
            'home_address',
            'city',
            'postal_code',
            'social_security_number',
            'tax_identification_number',
            'tax_service',
            'identity_number',
            'bank',
            'iban',
            'email:email',
            'birthdate',
            'birthplace',
            'comments:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    </div>