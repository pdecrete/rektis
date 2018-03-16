<?php

use app\modules\schooltransport\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\schooltransport\models\SchtransportTransportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->title = Module::t('modules/schooltransport/app', 'School Transportations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schtransport-transport-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Module::t('modules/schooltransport/app',  'Create Transportation'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'transport_id',
            'transport_submissiondate',
            'transport_startdate',
            'transport_enddate',
            'transport_teachers',
            // 'transport_students',
            // 'meeting_id',
            // 'school_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
