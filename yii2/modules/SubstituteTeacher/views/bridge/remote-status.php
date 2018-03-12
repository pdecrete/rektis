<?php

use yii\helpers\VarDumper;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $status array service_key => service_status */
?>
    <h1>Κατάσταση απομακρυσμένης υπηρεσίας</h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Ανανέωση', ['remote-status'], [
                'data' => [
                    'method' => 'post',
                ],
                'class' => 'btn btn-primary'
        ]);
        ?>
    </p>

    <?php if ($status === null) : ?>

    <p>Για εμφάνιση της τρέχουσας κατάστασης της απομακρυσμένης υπηρεσίας πατήστε <strong>Ανανέωση</strong>.</p>

    <?php else: ?>

    <h2>Κλήση</h2>
    <?php if ($status === true) : ?>
    <span class="label label-success">Επιτυχής</span>
    <?php else: ?>
    <span class="label label-danger">Αποτυχία
        <?= $status ?>
    </span>
    <?php endif; ?>

    <?php if ($status === true) : ?>
    <h2>Κατάσταση υπηρεσιών</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>Υπηρεσία</td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody>
            <tr class="<?= $services_status['applications'] == 1 ? 'success' : 'danger'?>">
                <td>Ανοικτό σε αιτήσεις</td>
                <td>
                    <?= $services_status['applications'] == 1 ? 'ΝΑΙ' : 'ΟΧΙ'?>
                </td>
            </tr>
            <tr class="<?= $services_status['load'] == 1 ? 'success' : 'danger'?>">
                <td>Ενεργοποιημένη δυνατότητα φόρτωσης στοιχείων</td>
                <td>
                    <?= $services_status['load'] == 1 ? 'ΝΑΙ' : 'ΟΧΙ'?>
                </td>
            </tr>
            <tr class="<?= $services_status['clear'] == 1 ? 'success' : 'danger'?>">
                <td>Ενεργοποιημένη δυνατότητα εκκαθάρισης στοιχείων</td>
                <td>
                    <?= $services_status['clear'] == 1 ? 'ΝΑΙ' : 'ΟΧΙ'?>
                </td>
            </tr>
            <tr class="<?= $services_status['unload'] == 1 ? 'success' : 'danger'?>">
                <td>Ενεργοποιημένη δυνατότητα λήψης στοιχείων</td>
                <td>
                    <?= $services_status['unload'] == 1 ? 'ΝΑΙ' : 'ΟΧΙ'?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>

    <h3>Πλήρης απάντηση</h3>
    <div class="well well-sm">
        <?= VarDumper::dumpAsString($data) ?>
    </div>

    <?php endif; ?>