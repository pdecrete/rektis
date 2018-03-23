<?php

use yii\bootstrap\Html;
use yii\helpers\VarDumper;
use yii\widgets\ActiveForm;
use yii\web\View;

?>
    <h1>Αποστολή στοιχείων</h1>

    <p>
        <?php echo Html::a('<span class="glyphicon glyphicon-refresh"></span> Κατάσταση υπηρεσιών', ['remote-status'], [
                'data' => [
                    'method' => 'post',
                ],
                'class' => 'btn btn-info'
        ]);
        ?>
        <?php if (!empty($call_model)) : ?>
        <?php echo Html::a('<span class="glyphicon glyphicon-arrow-up"></span> Αποστολή', ['send', 'call_id' => $call_model->id], [
                'data' => [
                    'method' => 'post',
                    'confirm' => 'Είστε σίγουροι;'
                ],
                'class' => 'btn btn-primary'
        ]);
        ?>
        <?php endif; ?>
    </p>

    <div class="well">
    <?php 
    $form = ActiveForm::begin([
            'id' => 'call-choose-form',
            'method' => 'GET',
            'action' => [
                'send',
            ],
            'options' => ['class' => 'form-horizontal'],
            'enableClientValidation' => false,
        ]);
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <?= Yii::t('substituteteacher', 'Calls') ?>
            </div>
            <div class="col-sm-6">
                <?= Html::dropDownList('call_id', $call_model ? $call_model->id : null, \app\modules\SubstituteTeacher\models\Call::defaultSelectables(), ['class' => 'form-control', 'prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>
            </div>
            <div class="col-sm-3">
                    <?=
                    Html::submitButton(Yii::t('substituteteacher', 'Choose call'), [
                        'class' => 'btn btn-primary',
                    ])
                    ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    </div>


    <?php if (!empty($call_model)) : ?>
    <h2>Στοιχεία για αποστολή
        <small>
            <em>Πρόσκληση:</em> <?php echo $call_model->title; ?> /
            <em>Έτος:</em> <?php echo $call_model->year; ?>
        </small>
    </h2>
    <table class="table table-bordered table-hover">
        <tbody>
            <tr>
                <th class="col-sm-4">Ζητούμενες προσλήψεις αναπληρωτών</th>
                <td>
                    <?= implode('<br/>', array_map(function ($m) {
                            return "{$m->teachers}, {$m->specialisation->label}";
                        }, $call_model->callTeacherSpecialisations))
                    ?>
                </td>
            </tr>
            <tr>
                <th>Κενά</th>
                <td>
                    <?= $count_call_positions ?>
                </td>
            </tr>
            <tr>
                <th>Αναπληρωτές</th>
                <td>
                <?= $count_teachers ?>
                <?php if (!empty($teacher_ids)) : ?>
                <?= 
                    Html::a(Yii::t('substituteteacher', 'Display teachers list'), '#', [
                        'class' => 'btn btn-sm btn-info',
                        'id' => 'teachers-list-btn'
                    ])
                ?>
                <div id="teachers-list-placeholder" style="padding-top: 1em;">
                    <?php
                        $options = [
                            'title' => Yii::t('substituteteacher', 'List of teachers'),
                            'subtitle' => Yii::t('substituteteacher', 'Selected for call'),
                            'type' => 'info',
                            'toolsTemplate' => '{myclose}',
                            'toolsButtons' => [
                                'myclose' => function() {
                                    $options = [
                                        'class' => 'btn btn-box-tool',
                                        'data' => [
                                            'widget' => 'remove',
                                        ],
                                    ];
                                    return Html::button('X', $options);
                                },
                            ],
                            'invisible' => false,
                            'bodyLoad' => ['fetch', 'what' => 'teacher'],
                            'autoload' => false,
                            'hidden' => true,
                            'data' => [
                                'ids' => implode(',', $teacher_ids)
                            ],
                            // 'clientOptions' => [
                            //    'autoload' => true, // modify this with the general option not here though
                            //    'onerror' => new \yii\web\JsExpression('function(response, box, xhr) {console.log(response,box,xhr)}'), // loads the error message in the box by default
                            //    'onload' => new \yii\web\JsExpression('function(box, status) { console.log(box,status); }'), // nothing by default
                            // ],
                            'classes' => ['box', 'box-flat', 'box-init'],
                        ];
                    ?>
                    <?= marekpetras\yii2ajaxboxwidget\Box::widget($options); ?>
                </div>
                <?php endif; ?>
                </td>
            </tr>
            <?php foreach ($teacher_counts as $tc) : ?>
            <tr>
                <td><?php echo $tc['specialisation']; ?>: θέσεις αναπληρωτών</td>
                <td><?= $tc['wanted'] ?></td>
            </tr>
            <tr>
                <td><?php echo $tc['specialisation']; ?>: αναζητήθηκαν</td>
                <td><?= $tc['extra_wanted'] ?></td>
            </tr>
            <tr class="<?= intval($tc['available']) < intval($tc['extra_wanted']) ? 'danger' : 'success' ?>">
                <td><?php echo $tc['specialisation']; ?>: εντοπίστηκαν</td>
                <td><?= $tc['available'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th>Προτιμήσεις τοποθέτησης</th>
                <td>
                    <?= $count_placement_preferences ?>
                </td>
            </tr>
            <tr>
                <th>Περιφερειακές ενότητες</th>
                <td>
                    <?= $count_prefectures ?>
                </td>
            </tr>
        </tbody>
    </table>

    <?php if ($status_clear !== null) : ?>

    <h2>Κλήση εκκαθάρισης προηγούμενων στοιχείων</h2>
    <?php if ($status_clear === true) : ?>
    <span class="label label-success">Επιτυχής</span>
    <?php else: ?>
    <div class="alert alert-danger">
        <span class="label label-danger">Αποτυχία
            <?= $status_clear ?>
        </span>
    </div>
    <?php endif; ?>

    <h3>Πλήρης απάντηση</h3>
    <div class="well well-sm">
        <?php echo VarDumper::dumpAsString($response_data_clear) ?>
    </div>

    <?php endif; ?>

    <?php if ($status_load !== null) : ?>

    <h2>Κλήση αποστολής στοιχείων</h2>
    <?php if ($status_load === true) : ?>
    <span class="label label-success">Επιτυχής</span>
    <p>Αναφερόμενος αριθμός στοιχείων που λήφθηκαν από την απομακρυσμένη υπηρεσία:</p>
    <ul>
        <li>Κενά
            <span class="badge">
                <?php echo $response_data_load['count']['positions']; ?>
            </span> /
            <span class="badge">
                <?php echo $count_call_positions; ?>
            </span>
            <?php if ($response_data_load['count']['positions'] != $count_call_positions) : ?>
            <span class="label label-danger">Ασυμφωνία!</span>
            <?php endif; ?>
        </li>
        <li>Αναπληρωτές
            <span class="badge">
                <?php echo $response_data_load['count']['teachers']; ?>
            </span> /
            <span class="badge">
                <?php echo $count_teachers; ?>
            </span>
            <?php if ($response_data_load['count']['teachers'] != $count_teachers) : ?>
            <span class="label label-danger">Ασυμφωνία!</span>
            <?php endif; ?>
        </li>
        <li> Προτιμήσεις τοποθέτησης
            <span class="badge">
                <?php echo $response_data_load['count']['placement_preferences']; ?>
            </span> /
            <span class="badge">
                <?php echo $count_placement_preferences; ?>
            </span>
            <?php if ($response_data_load['count']['placement_preferences'] != $count_placement_preferences) : ?>
            <span class="label label-danger">Ασυμφωνία!</span>
            <?php endif; ?>
        </li>
        <li>Περιφερειακές ενότητες
            <span class="badge">
                <?php echo $response_data_load['count']['prefectures']; ?>
            </span> /
            <span class="badge">
                <?php echo $count_prefectures; ?>
            </span>
            <?php if ($response_data_load['count']['prefectures'] != $count_prefectures) : ?>
            <span class="label label-danger">Ασυμφωνία!</span>
            <?php endif; ?>
        </li>
    </ul>
    <?php else: ?>
    <div class="alert alert-danger">
        <span class="label label-danger">Αποτυχία
            <?= $status_load ?>
        </span>
    </div>
    <?php endif; ?>

    <h3>Πλήρης απάντηση</h3>
    <div class="well well-sm">
        <?php echo VarDumper::dumpAsString($response_data_load) ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

<?php 
$teachers_load_btn_js = <<< TEACHERS_LOAD_BTN
$('#teachers-list-btn').on('click', function(e) {
    e.preventDefault();
    $('#teachers-list-placeholder .box-init').each(function(){
        $(this).box('hide').box('reload').box('show');
    });
});
TEACHERS_LOAD_BTN;
$this->registerJs($teachers_load_btn_js, View::POS_READY, 'teachers-list-display');
?>