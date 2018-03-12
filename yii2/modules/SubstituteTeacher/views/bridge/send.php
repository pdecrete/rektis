<?php

use yii\bootstrap\Html;
use yii\helpers\VarDumper;

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
        <?php echo Html::a('<span class="glyphicon glyphicon-arrow-up"></span> Αποστολή', ['send'], [
                'data' => [
                    'method' => 'post',
                    'confirm' => 'Είστε σίγουροι;'
                ],
                'class' => 'btn btn-primary'
        ]);
        ?>
    </p>

    <h2>Στοιχεία για αποστολή</h2>
    <p>CALL:
        <strong>TODO
            <?php echo $call_id; ?>
        </strong>
    </p>
    <p>YEAR:
        <strong>TODO
            <?php echo $year; ?>
        </strong>
    </p>
    <table class="table table-bordered table-hover">
        <tbody>
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