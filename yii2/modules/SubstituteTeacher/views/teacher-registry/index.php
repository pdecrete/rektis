<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use app\models\Specialisation;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\TeacherRegistrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Teacher Registries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-registry-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Create Teacher Registry'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'specialisation_id',
                'value' => 'specialisation.label',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'specialisation_id',
                    'data' => Specialisation::selectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            // 'gender',
            'surname',
            'firstname',
            // 'fathername',
            // 'mothername',
            // 'marital_status',
            // 'protected_children',
            'mobile_phone',
            // 'home_phone',
            // 'work_phone',
            // 'home_address',
            // 'city',
            // 'postal_code',
            // 'social_security_number',
            // 'tax_identification_number',
            // 'tax_service',
            // 'identity_number',
            // 'bank',
            // 'iban',
            'email:email',
            // 'birthdate',
            // 'birthplace',
            // 'comments:ntext',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'delete' => \Yii::$app->user->can('admin'),
                ],
            ],
        ],
    ]); ?>
</div>
