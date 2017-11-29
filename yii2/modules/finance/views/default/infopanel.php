<?php

use yii\bootstrap\Html;
use app\modules\finance\components\Integrity;

?>

<div class="panel panel-info">
    <div class="panel-heading">
    	
        <div class="container">
          	<span >Εργάζεστε στο οικονομικό έτος <strong><?php echo \Yii::$app->session["working_year"];?></strong></span>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Πληροφορίες</button>
    	    <div id="demo" class="collapse">
            <p><?php ; //echo Integrity::currentYearKaesCount(); ?></p>
          	</div>
        </div>    	
    </div>
</div>