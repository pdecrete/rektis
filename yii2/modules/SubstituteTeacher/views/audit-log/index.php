<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\AuditLog;
use app\components\FilterActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\AuditLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Audit Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-log-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a(Html::icon('refresh') . ' Reload', ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'level',
                'value' => 'level_label',
                'filter' => Html::activeDropDownList($searchModel, 'level', AuditLog::filterOptions('level'), ['class' => 'form-control', 'prompt' => 'Όλα']),
                'format' => 'html'
            ],
            'category',
            [
                'attribute' => 'log_time',
                'format' => 'datetime',
                'filter' => false
            ],
            'prefix:ntext',
            [
                'attribute' => 'user',
                'value' => function ($model) {
                    return $model->user ? $model->user->username : null;
                }
            ],
            // 'message:ntext',
            [
                'class' => FilterActionColumn::className(),
                'filter' => FilterActionColumn::LINK_INDEX_CONFIRM,
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'template' => '{view}'
            ],
        ],
    ]); ?>
</div>