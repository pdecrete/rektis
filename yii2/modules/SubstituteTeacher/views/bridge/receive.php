<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
?>
<h1>bridge/receive</h1>

<p>
    <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Κατάσταση υπηρεσιών', ['remote-status'], [
                'data' => [
                    'method' => 'post',
                ],
                'class' => 'btn btn-primary'
        ]);
        ?>
</p>

<?= $this->render('_connection_information', ['connection_options' => $connection_options]) ?>