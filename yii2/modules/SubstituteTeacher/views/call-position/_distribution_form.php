<?php

use yii\grid\GridView;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\bootstrap\Html;
use yii\helpers\Url;
use app\modules\SubstituteTeacher\models\Position;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$positionsDataProvider->pagination->pageParam = 'pos-page';
$positionsDataProvider->sort->sortParam = 'pos-sort';

$callPositionsDataProvider->pagination->pageParam = 'call-pos-page';
$callPositionsDataProvider->sort->sortParam = 'call-pos-sort';

?>

<div class="call-distribution-form">
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $positionsDataProvider,
        'filterModel' => $positionsSearchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'title',
            [
                'attribute' => 'school_type',
                'value' => 'school_type_label',
                'label' => Yii::t('substituteteacher', 'Sch.Type'),
                'filter' => Select2::widget([
                    'model' => $positionsSearchModel,
                    'attribute' => 'school_type',
                    'data' => \app\modules\SubstituteTeacher\models\Position::getSchoolTypeChoices(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'operation_id',
                'value' => 'operation.title',
                'filter' => Select2::widget([
                    'model' => $positionsSearchModel,
                    'attribute' => 'operation_id',
                    'data' => \app\modules\SubstituteTeacher\models\Operation::defaultSelectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'specialisation_id',
                'value' => 'specialisation.code',
                'filter' => Select2::widget([
                    'model' => $positionsSearchModel,
                    'attribute' => 'specialisation_id',
                    'data' => app\models\Specialisation::selectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'prefecture_id',
                'value' => 'prefecture.prefecture',
                'filter' => Select2::widget([
                    'model' => $positionsSearchModel,
                    'attribute' => 'prefecture_id',
                    'data' => app\modules\SubstituteTeacher\models\Prefecture::defaultSelectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
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
            [
                'attribute' => 'remaining',
                'filter' => false
            ],
//                'covered_teachers_count',
//                'covered_hours_count',
            // 'whole_teacher_hours',
            // 'created_at',
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{add} {addpartial}',
                'buttons' => [
                    'add' => function ($url, $model, $key) use ($callModel) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-plus"></span>',
                            Url::to(['distribution-add',
                                    'CallPosition' => [
                                        'call_id' => $callModel->id,
                                        'position_id' => $model->id,
                                        'teachers_count' => $model->position_has_type === app\modules\SubstituteTeacher\models\Position::POSITION_TYPE_TEACHER ? $model->remaining : 0,
                                        'hours_count' => $model->position_has_type === app\modules\SubstituteTeacher\models\Position::POSITION_TYPE_HOURS ? $model->remaining : 0,
                                    ],
                                ]),
                            [
                                'title' => Yii::t('substituteteacher', 'Add whole of remaining to current distribution'),
                                'data-method' => 'post',
                                'class' => 'btn btn-sm btn-success'
                                ]
                        );
                    },
                    'addpartial' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-option-horizontal"></span>', '#', [
                                'title' => Yii::t('substituteteacher', 'Add part of remaining to current distribution'),
                                'data' => [
                                    'toggle' => 'modal',
                                    'target' => '#choose-remaining-modal',
                                    'position_id' => $model->id,
                                    'teachers_count' => $model->position_has_type === app\modules\SubstituteTeacher\models\Position::POSITION_TYPE_TEACHER ? $model->remaining : 0,
                                    'hours_count' => $model->position_has_type === app\modules\SubstituteTeacher\models\Position::POSITION_TYPE_HOURS ? $model->remaining : 0,
                                ],
                                'class' => 'btn btn-sm btn-warning'
                        ]);
                    },
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ]
            ],
        ],
    ]);

    ?>
    <?php Pjax::end(); ?>

    <hr/>

    <?= Html::beginForm(['distribution-group', 'call' => $callModel->id], 'post'); ?>
    <?php // Pjax::begin();?>
    <?=
    GridView::widget([
        'dataProvider' => $callPositionsDataProvider,
        'filterModel' => $callPositionsSearchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            // 'call_id',
            // 'position_id',
            'position.title',
            [
                'attribute' => 'position.school_type',
                'value' => 'position.school_type_label',
                'label' => Yii::t('substituteteacher', 'Sch.Type')
            ],
            'teachers_count',
            'hours_count',
            [
                'attribute' => 'group',
                'value' => function ($m) {
                    return '<span class="label" style="background-color: hsl('
                        . (($m->group * 25 % 360) + 20) . ','
                        . (($m->group == 0) ? '0%,90%' : '100%,50%') . ')">'
                        . (($m->group == 0) ? Yii::t('substituteteacher', 'No group') : Yii::t('substituteteacher', 'In group'))
                        . '</span>';
                },
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'format' => 'html'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{ungroup} {remove}',
                'buttons' => [
                    'remove' => function ($url, $model, $key) use ($callModel) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                            Url::to(['distribution-remove', 'id' => $model->id]),
                            [
                                'title' => Yii::t('substituteteacher', 'Remove from current distribution'),
                                'data-method' => 'post',
                                'class' => 'btn btn-xs btn-danger'
                                ]
                        );
                    },
                    'ungroup' => function ($url, $model, $key) use ($callModel) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-log-out"></span>',
                            Url::to(['distribution-ungroup', 'id' => $model->id]),
                            [
                                'title' => Yii::t('substituteteacher', 'Ungroup this item'),
                                'data-method' => 'post',
                                'class' => 'btn btn-sm btn-info'
                                ]
                        );
                    },
                ],
                'visibleButtons' => [
                    'ungroup' => function ($model, $key, $index) {
                        return $model->group != 0;
                    }
                ],
                'contentOptions' => [
                    'class' => 'text-right'
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'contentOptions' => [
                    'class' => 'text-center'
                ]
            ],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'group_ids',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    $options = [];
                    if ($model->group > 0) {
                        $options['disabled'] = 'disabled';
                    }
                    return $options;
                },
            ],
        ],
    ]);

    ?>
    <?= Html::submitButton('<span class="glyphicon glyphicon-paperclip"></span> ' . Yii::t('substituteteacher', 'Group selected'), ['class' => 'btn btn-info pull-right']) ?>
    <?php // Pjax::end();?>
    <?= Html::endForm(); ?> 
</div>

<?php
Modal::begin([
    'id' => 'choose-remaining-modal',
    'header' => '<h3>' . Yii::t('substituteteacher', 'Fill in the offered position information') . '</h3>',
]);

$form = ActiveForm::begin([
        'id' => 'remaining-choose-form',
        'method' => 'GET',
        'action' => [
            'distribution-add',
        ],
        'enableClientValidation' => false,
    ]);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <?= Yii::t('substituteteacher', 'Offered part') ?>
        </div>
        <div class="col-sm-8">
            <?= Html::hiddenInput('CallPosition[call_id]', $callModel->id) ?>
            <?= Html::hiddenInput('CallPosition[position_id]', 'void', ['id' => 'modal_input_position_id']) ?>
            <?= Html::hiddenInput('CallPosition[teachers_count]', 'void', ['id' => 'modal_input_teachers_count', 'min' => 0, 'class' => 'form-control']) ?>
            <?= Html::hiddenInput('CallPosition[hours_count]', 'void', ['id' => 'modal_input_hours_count', 'min' => 0, 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <p class="text-warning"><?= Yii::t('substituteteacher', 'Fill in a value from 0 up to a maximum of <strong><span id="maxoffered">{n}</span></strong>', ['n' => '']) ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-right">
            <div class="form-group">
                <?=
                Html::button(Yii::t('substituteteacher', 'Cancel'), [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'toggle' => 'modal',
                        'target' => '#choose-remaining-modal',
                    ],
                ])

                ?>
                <?=
                Html::submitButton(Yii::t('substituteteacher', 'Import'), [
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

$fill_modal_js = "$('#choose-remaining-modal').on('show.bs.modal', function (e) {
    var button = $(e.relatedTarget);
    var modal = $(this);
    modal.find('#modal_input_position_id').val(button.data('position_id'))
    modal.find('#modal_input_teachers_count').val(button.data('teachers_count'))
    modal.find('#modal_input_hours_count').val(button.data('hours_count'))
    if (parseInt(button.data('teachers_count')) > 0) {
        modal.find('#maxoffered').text(button.data('teachers_count'))
        modal.find('#modal_input_teachers_count').prop('type', 'number');
        modal.find('#modal_input_hours_count').prop('type', 'hidden');
    } else {
        modal.find('#maxoffered').text(button.data('hours_count'))
        modal.find('#modal_input_hours_count').prop('type', 'number');
        modal.find('#modal_input_teachers_count').prop('type', 'hidden');
    }
})";
$this->registerJs($fill_modal_js);
