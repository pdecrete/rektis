<?php

use app\modules\eduinventory\EducationInventoryModule;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TeacherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => EducationInventoryModule::t('modules/eduinventory/app', 'Educational Data'), 'url' => ['/eduinventory']];
$this->title = Yii::t('app', 'Teachers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">
    	<?= Html::a(EducationInventoryModule::t('modules/eduinventory/app', 'Import Teachers from Excel'), ['import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Create Teacher'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'teacher_surname',
            'teacher_name',
            'teacher_fathername',
            'teacher_mothername',
            'teacher_registrynumber',
            'teacher_afm',
            ['class' => 'yii\grid\ActionColumn', 'contentOptions' => ['class'=> 'text-center text-nowrap']],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
