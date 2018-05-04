<?php

use yii\bootstrap\Html;
use yii\helpers\VarDumper;

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
    <?php echo Html::a('<span class="glyphicon glyphicon-arrow-down"></span> Λήψη', ['receive'], [
                'data' => [
                    'method' => 'post',
                    'confirm' => 'Είστε σίγουροι;'
                ],
                'class' => 'btn btn-primary'
        ]);
        ?>
</p>

<?= $this->render('_connection_information', ['connection_options' => $connection_options]) ?>

<?php if ($status_unload !== null) : ?>

<h2>Κλήση λήψης στοιχείων</h2>
<?php if ($status_unload === true) : ?>
<span class="label label-success">Επιτυχής</span>
<p>Αναφερόμενος αριθμός στοιχείων που λήφθηκαν από την απομακρυσμένη υπηρεσία:</p>
<ul>
    <li>
        <?= $message ?>
    </li>
</ul>
<?php else: ?>
<div class="alert alert-danger">
    <span class="label label-danger">Αποτυχία
        <?= $status_unload ?>
    </span>
</div>
<?php endif; ?>

<?php endif; ?>
<?php
