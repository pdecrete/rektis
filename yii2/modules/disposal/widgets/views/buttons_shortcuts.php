<?php
use app\modules\disposal\DisposalModule;
use yii\helpers\Html;

?>
<div class="btn-group">
	<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
		<?= DisposalModule::t('modules/disposal/app', 'View'); ?> <span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<li><?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposals for Approval-Decision'), ['disposal/index']) ?></li>
		<li><?= Html::a(DisposalModule::t('modules/disposal/app', 'Approved Disposals'), ['disposal/index', 'archived' => 1]) ?></li>
		<li><?= Html::a(DisposalModule::t('modules/disposal/app', 'Rejected Disposals'), ['disposal/index', 'rejected' => 1]) ?></li>
		<li class="divider"></li>
		<li><?= Html::a(DisposalModule::t('modules/disposal/app', 'Approvals-Decisions'), ['disposal-approval/index']) ?></li>
		<li><?= Html::a(DisposalModule::t('modules/disposal/app', 'Archived Approvals-Decisions'), ['disposal-approval/index', 'archived' => 1]) ?></li>
		<li class="divider"></li>
		<li><?= Html::a(DisposalModule::t('modules/disposal/app', 'Directorate Decisions'), ['disposal-localdirdecision/index']) ?></li>
	</ul>
</div>