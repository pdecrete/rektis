<?php
use app\modules\finance\Module;
use app\modules\finance\models\FinanceYear;
use app\modules\finance\components\Money;

?>
<div class="row">

	<div class="col-lg-4">&nbsp;</div>
	<div class="col-lg-4">&nbsp;</div>
	<div class="col-lg-4">
    	<div class="panel panel-default">
        	<div class="panel-heading">
              	<span>
              		<?= Module::t('modules/finance/app', 'Currently Working Year');?><strong>: <?= Yii::$app->session["working_year"] ?></strong>
              	</span>
              	&nbsp;&nbsp;&nbsp;
              	<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#demo">
              		<?= Module::t('modules/finance/app', 'Information');?>
          		</button>
        	    <div id="demo" class="collapse">
                <hr />
                <div class="row">
                    <div class="col-lg-6"><strong><?= Module::t('modules/finance/app', 'Initial Credit');?>:</strong></div>
                    <div class="col-lg-6"><?= Money::toCurrency(FinanceYear::getYearCredit(Yii::$app->session["working_year"]), true);?></div>
                 	</div>
             	</div>
            </div>
        </div>
    </div>
</div>