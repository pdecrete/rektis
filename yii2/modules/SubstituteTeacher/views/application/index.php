<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\Call;
use kartik\select2\Select2;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\TeacherBoard;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Applications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'call_id',
                'value' => 'call.title',
                'filter' => Call::defaultSelectables()
            ],
            [
                'attribute' => 'teacher_board_id',
                'value' => function ($m) {
                    return empty($m->teacherBoard) ? null : $m->teacherBoard->teacher->name . '<br>' . $m->teacherBoard->label;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'teacher_board_id',
                    'data' => TeacherBoard::selectables('id', 'teacher.name'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
                'format' => 'html'
            ],
            'agreed_terms_ts:datetime',
            [
                'attribute' => 'state',
                'value' => 'state_label',
                'format' => 'html'
            ],
            // 'reference:ntext',
            'deleted:boolean',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
            ],
        ],
    ]); ?>
</div>