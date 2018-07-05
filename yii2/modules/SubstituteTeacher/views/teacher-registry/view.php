<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\Teacher;
use yii\data\ArrayDataProvider;

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

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#personal" aria-controls="personal" role="tab" data-toggle="tab"><?= Yii::t('substituteteacher', 'Personal information') ?></a></li>
        <li role="presentation"><a href="#financial" aria-controls="financial" role="tab" data-toggle="tab"><?= Yii::t('substituteteacher', 'Financial information') ?></a></li>
        <li role="presentation"><a href="#qualification" aria-controls="qualification" role="tab" data-toggle="tab"><?= Yii::t('substituteteacher', 'Qualifications') ?></a></li>
        <li role="presentation"><a href="#teacher-info" aria-controls="teacher-info" role="tab" data-toggle="tab"><?= Yii::t('substituteteacher', 'Appearances') ?></a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade-in active" id="personal">
            <h1><?= Yii::t('substituteteacher', 'Personal information') ?></h1>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    // 'id',
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
                    'identity_number',
                    'email:email',
                    'ama',
                    'efka_facility',
                    'municipality',
                    'birthdate',
                    'birthplace',
                    'comments:ntext',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
        </div>
        <div role="tabpanel" class="tab-pane fade-in" id="financial">
            <h1><?= Yii::t('substituteteacher', 'Financial information') ?></h1>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'tax_identification_number',
                    'tax_service',
                    'bank',
                    'iban',
                ],
            ]) ?>
        </div>
        <div role="tabpanel" class="tab-pane fade-in" id="qualification">
            <h1><?= Yii::t('substituteteacher', 'Qualifications') ?></h1>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'sign_language:boolean',
                    'braille:boolean',
                    'iek:boolean',
                    'epal:boolean',
                    'tei:boolean',
                    'aei:boolean',
                ],
            ]) ?>
        </div>
        <div role="tabpanel" class="tab-pane fade-in" id="teacher-info">
            <h1><?= Yii::t('substituteteacher', 'Appearances') ?></h1>
            <?php if (empty($model->teachers)) : ?>
            <p class="text-info"><?= Yii::t('substituteteacher', 'No information') ?></p>
            <?php else : ?>
            <?php 
            $dataProvider2 = new ArrayDataProvider([
                'allModels' => $model->getTeachers()->orderBy(['year' => SORT_DESC])->all(),
            ]);
            ?>
            <div class="teacher-index">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider2,
                    'filterModel' => null,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'year',
                        [
                            'attribute' => 'status',
                            'value' => 'status_label',
                        ],
                        [
                            'attribute' => '',
                            'header' => Yii::t('substituteteacher', 'Teacher boards'),
                            'value' => function ($m) {
                                return $m->boards ? implode(
                                    '<br>',
                                    array_map(function ($model) {
                                        return $model->label;
                                    }, $m->boards)
                                ) : null;
                            },
                            'format' => 'html'
                        ],
                        [
                            'attribute' => '',
                            'header' => Yii::t('substituteteacher', 'Placement preferences'),
                            'value' => function ($m) {
                                return $m->placementPreferences ? implode(
                                    '<br>',
                                    array_map(function ($pref) {
                                        return $pref->label_for_teacher;
                                    }, $m->placementPreferences)
                                ) : null;
                            },
                            'format' => 'html'
                        ],
                    ],
                ]); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

