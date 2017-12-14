<?php
use app\modules\finance\Module;

?>
<div class="container">
	<div class="row">
		<div class="col-lg-3">&nbsp;</div>
		<div class="col-lg-3">&nbsp;</div>
		<div class="col-lg-3">&nbsp;</div>
		<div class="col-lg-3">
        	<div class="panel panel-info">
            	<div class="panel-heading">
                  	<span>
                  		<?= Module::t('modules/finance/app', 'Currently Working Year');?><strong>: <?= Yii::$app->session["working_year"] ?></strong>
                  	</span>
                  	&nbsp;&nbsp;&nbsp;
                  	<!--  button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo" -->
                  		<?php //Module::t('modules/finance/app', 'Information');?>
              		<!--  /button -->
            	    <div id="demo" class="collapse">
                    <p> </p>
                 	</div>
                </div>
            </div>
        </div>
    </div>
</div>
