<?php

use yii\grid\GridView;
use yii\bootstrap\Html;
use kartik\select2\Select2;
use app\modules\SubstituteTeacher\models\Teacher;
use yii\helpers\Json;
use app\components\FilterActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\TeacherStatusAuditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Teacher Status Audits');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-status-audit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (\Yii::$app->user->can('admin')) : ?>
    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Create Teacher Status Audit'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'teacher_id', 
                'value' => function ($m) {
                    return Html::a(Html::icon('user'), ['teacher/view', 'id' => $m->teacher_id], ['class' => 'btn btn-xs btn-default', 'title' => Yii::t('substituteteacher', 'View teacher entry')]) 
                        . " {$m->teacher->name} (id: {$m->teacher_id})";
                },
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'teacher_id',
                    'data' => Teacher::selectables('id', 'name'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'status',
            'audit_ts',
            'audit',
            // 'data:ntext',
            [
                'attribute' => 'data',
                'value' => function ($model) {
                    return empty($model->data) ? null : "<pre>" . Json::encode($model->data_parsed, JSON_PRETTY_PRINT) . "</pre>";
                },
                'format' => 'html'
            ],

            [
                'class' => FilterActionColumn::className(),
                'filter' => FilterActionColumn::LINK_INDEX_CONFIRM,
                'template' => '{update} {delete}',
                'visible' => \Yii::$app->user->can('admin')
            ],
        ],
    ]); ?>
</div>
