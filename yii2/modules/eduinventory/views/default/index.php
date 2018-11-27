<?php

use app\modules\eduinventory\EducationInventoryModule;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = EducationInventoryModule::t('modules/eduinventory/app', 'Educational Data');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="body-content">
  	<div class="row">
        <div class="col-lg-4">
            <h3><?= EducationInventoryModule::t('modules/eduinventory/app', 'Teachers');?></h3>
            <p><?= EducationInventoryModule::t('modules/eduinventory/app', 'View/edit/delete teachers');?></p>
			<p><?= Html::a(EducationInventoryModule::t('modules/eduinventory/app', 'Teachers'), Url::to(['/eduinventory/teacher/']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
  	</div>
</div>
