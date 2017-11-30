<?php

use yii\bootstrap\Html;
use app\modules\finance\components\Integrity;
use app\modules\finance\models\FinanceYear;
use app\modules\finance\models\FinanceKaecredit;

?>

<div class="panel panel-info">
    <div class="panel-heading">
    	
        <div class="container">
          	<span >Εργάζεστε στο οικονομικό έτος <strong><?php echo \Yii::$app->session["working_year"];?></strong></span>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Πληροφορίες</button>
    	    <div id="demo" class="collapse">
            <p><?php echo FinanceYear::getYearCredit(2017); 
            //var_dump($expression)         
            echo FinanceKaecredit::getSumKaeCredits(2017);
            ?></p>
          	</div>
        </div>    	
    </div>
</div>