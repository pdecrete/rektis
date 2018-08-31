<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\disposal\models\DisposalApprovalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = DisposalModule::t('modules/disposal/app', 'Disposals Approvals');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-approval-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'approval_id',
            'approval_regionaldirectprotocol',
            'approval_localdirectprotocol',
            'approval_notes',
            'approval_file',
            // 'approval_signedfile',
            // 'approval_created_at',
            // 'approval_updated_at',
            // 'approval_created_by',
            // 'approval_updated_by',
            // 'approvaltype_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
