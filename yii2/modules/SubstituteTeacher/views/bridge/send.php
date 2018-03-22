<?php

use yii\bootstrap\Html;
use yii\helpers\VarDumper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
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
                <th class="col-sm-6">Ζητούμενες προσλήψεις αναπληρωτών</th>
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
    