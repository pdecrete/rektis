<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\Teacher;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\TeacherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Teachers');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="teacher-index">
        <h1>
            <?= Html::encode($this->title) ?>
        </h1>

        <p>
            <?= Html::a(Yii::t('substituteteacher', 'Create Teacher'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'registry_id',
                'value' => 'registry.name',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'registry_id',
                    'data' => TeacherRegistry::selectables('id', 'name'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'year',
            [
                'attribute' => 'status',
                'value' => 'status_label',
                'filter' => Teacher::getChoices('status')
            ],
            'points',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>