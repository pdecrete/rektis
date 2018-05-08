<?php

use app\modules\schooltransport\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\schooltransport\models\SchtransportMeetingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'Parameters'), 'url' => ['/finance/default/parameterize']];
$this->title = Module::t('modules/schooltransport/app', 'Deductions');
$this->params['breadcrumbs'][] = $this->title;

$this->title = Yii::t('app', 'Schtransport Meetings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schtransport-meeting-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Schtransport Meeting'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'meeting_id',
            'meeting_city',
            'meeting_country',
            'meeting_startdate',
            'meeting_enddate',
            // 'program_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
