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
    <p>CALL: <strong>TODO $call</strong></p>
    <p>YEAR: <strong>TODO $year</strong></p>
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

    <?php if ($status !== null) : ?>

    <h2>Κλήση</h2>
    <?php if ($status === true) : ?>
    <span class="label label-success">Επιτυχής</span>
    <?php else: ?>
    <span class="label label-danger">Αποτυχία
        <?= $status ?>
    </span>
    <?php endif; ?>

    <?php if ($status === true) : ?>
    <h2>Κατάσταση αποστολής στοιχείων</h2>
    <?php endif; ?>

    <h3>Πλήρης απάντηση</h3>
    <div class="well well-sm">
        <?php echo VarDumper::dumpAsString($response_data) ?>
    </div>

    <?php endif; ?>