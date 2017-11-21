<?php

use yii\bootstrap\Html;

?>

<div class="panel panel-info">
    <div class="panel-heading">
    	
        <div class="container">
          	<span >Εργάζεστε στο οικονομικό έτος <strong><?php echo \Yii::$app->session["working_year"];?></strong></span>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Πληροφορίες</button>
    	    <div id="demo" class="collapse">
            <p>Πληροφορίες ΚΑΕ</p>
          	</div>
        </div>    	
    </div>
</div>