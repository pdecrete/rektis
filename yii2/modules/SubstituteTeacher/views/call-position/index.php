<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\CallPositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Call Positions');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="call-position-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?=
        Html::button(Yii::t('substituteteacher', 'Manage Distributions'), [
            'class' => 'btn btn-primary',
            'data' => [
                'toggle' => 'modal',
                'target' => '#choose-call-modal',
            ],
        ])

        ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            [
                'attribute' => 'call_id',
                'value' => 'call.label',
                'enableSorting' => false,
                'filter' => \app\modules\SubstituteTeacher\models\Call::defaultSelectables()
            ],
            [
                'attribute' => 'position_id',
                'value' => 'position.title',
                'enableSorting' => false,
                'filter' => false, // \app\modules\SubstituteTeacher\models\Position::defaultSelectables()
            ],
            [
                'attribute' => 'teachers_count',
                'value' => function ($m) {
                    return $m->teachers_count == 0 ? null : $m->teachers_count;
                },
                'filter' => false
            ],
            [
                'attribute' => 'hours_count',
                'value' => function ($m) {
                    return $m->hours_count == 0 ? null : $m->hours_count;
                },
                'filter' => false
            ],
            // 'created_at',
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}'
            ],
        ],
    ]);

    ?>
</div>


<?php
Modal::begin([
    'id' => 'choose-call-modal',
    'header' => '<h3>' . Yii::t('substituteteacher', 'Select call') . '</h3>',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);

$form = ActiveForm::begin([
        'id' => 'operation-call-form',
        'method' => 'GET',
        'action' => [
            'distribute',
        ],
        'options' => ['class' => 'form-horizontal'],
        'enableClientValidation' => false,
    ]);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <?= Yii::t('substituteteacher', 'Calls') ?>
        </div>
        <div class="col-sm-6">
            <?= Html::dropDownList('call', null, \app\modules\SubstituteTeacher\models\Call::defaultSelectables(), ['class' => 'form-control']) ?>
        </div>
    </div>
    <div class="row">
        <p>&nbsp;</p>
    </div>
    <div class="row">
        <div class="col-sm-12 text-right">
            <div class="form-group">
                <?=
                Html::button(Yii::t('substituteteacher', 'Cancel'), [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'toggle' => 'modal',
                        'target' => '#choose-call-modal',
                    ],
                ])

                ?>
                <?=
                Html::submitButton(Yii::t('substituteteacher', 'Continue'), [
                    'class' => 'btn btn-primary',
                ])

                ?>
            </div>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
Modal::end();
