<?php

use yii\bootstrap\Html;
use app\modules\finance\Module;
use app\modules\finance\components\Integrity;
use app\modules\finance\models\FinanceYear;
use app\modules\finance\models\FinanceKaecredit;

?>

<div class="panel panel-info">
    <div class="panel-heading">
    	
        <div class="container">
          	<span>
          		<?= Module::t('modules/finance/app', 'Currently Working Year');?><strong>: <?= Yii::$app->session["working_year"] ?></strong>
          	</span>
          	&nbsp;&nbsp;&nbsp;
          	<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">
          		<?= Module::t('modules/finance/app', 'Information');?>
      		</button>
    	    <div id="demo" class="collapse">
            <p> </p>
          	</div>
        </div>    	
    </div>
</div>