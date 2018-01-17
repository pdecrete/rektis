<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\TeacherRegistrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Teacher Registries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-registry-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Create Teacher Registry'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'specialisation_id',
            'gender',
            'surname',
            'firstname',
            // 'fathername',
            // 'mothername',
            // 'marital_status',
            // 'protected_children',
            // 'mobile_phone',
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
            // 'email:email',
            // 'birthdate',
            // 'birthplace',
            // 'comments:ntext',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
