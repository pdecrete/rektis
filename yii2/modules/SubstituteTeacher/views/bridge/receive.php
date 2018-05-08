<?php

use yii\bootstrap\Html;

?>
<h1>Λήψη στοιχείων από το σύστημα αιτήσεων</h1>

<p>
    <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Κατάσταση υπηρεσιών', ['remote-status'], [
                'data' => [
                    'method' => 'post',
                ],
                'class' => 'btn btn-info'
        ]);
        ?>
    <?php if (!empty($call_model)) : ?>
    <?php echo Html::a('<span class="glyphicon glyphicon-arrow-down"></span> Λήψη', ['receive', 'call_id' => $call_model->id], [
                'data' => [
                    'method' => 'post',
                    'confirm' => "Θα γίνει λήψη στοιχείων για την πρόσκληση: {$call_model->title}/{$call_model->year}. Είστε σίγουροι;"
                ],
                'class' => 'btn btn-primary'
        ]);
        ?>
    <?php endif; ?>
</p>

<?= $this->render('_connection_information', ['connection_options' => $connection_options]) ?>

<?= $this->render('_call_selection_form', ['route' => ['receive'], 'call_id' => $call_model ? $call_model->id : null]) ?>

<?php if (!empty($call_model)) : ?>
<h2>Λήψη στοιχείων πρόσκλησης
    <small>
        <em>Πρόσκληση:</em>
        <?php echo $call_model->title; ?>/
        <em>Έτος:</em>
        <?php echo $call_model->year; ?>
    </small>
</h2>
<p>Πατήστε το κουμπί Λήψη για να γίνει η λήψη των στοιχείων</p>
<?php endif; ?>

<?php if ($status_unload !== null) : ?>

<h2>Κλήση λήψης στοιχείων</h2>
<?php if ($status_unload === true) : ?>
<p>
    <span class="label label-success">Επιτυχής</span>
</p>
<p>Αναφερόμενος αριθμός στοιχείων που λήφθηκαν από την απομακρυσμένη υπηρεσία:</p>
<ul>
    <li>
        <?= $message_unload ?>
    </li>
</ul>
<?php else: ?>
<div class="alert alert-danger">
    <p>
        <span class="label label-danger">Αποτυχία
            <?= $status_unload ?>
        </span>
    </p>
</div>
<?php endif; ?>

<h2>Διαχείριση στοιχείων</h2>
<?php if ($status_data === true) : ?>
<p>
    <span class="label label-success">Επιτυχής</span>
</p>
<p>Τα στοιχεία που ελήφθησαν αποκωδικοποιήθηκαν και διαχειρίστηκαν με επιτυχία:</p>
<ul>
    <li>
        <?php echo implode('</li><li>', $messages_data); ?></li>
</ul>
<?php else: ?>
<div class="alert alert-danger">
    <p>
        <span class="label label-danger">Αποτυχία</span>
    </p>
    <p>Τα στοιχεία που ελήφθησαν προκάλεσαν σφάλμα:</p>
    <ul>
        <li>
            <?php echo implode('</li><li>', $messages_data); ?></li>
    </ul>
</div>
<?php endif; ?>

<?php endif; ?>
<?php
