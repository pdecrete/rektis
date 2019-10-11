<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use app\modules\SubstituteTeacher\models\Teacher;
use yii\bootstrap\ButtonDropdown;

$bundle = \app\modules\SubstituteTeacher\assets\ModuleAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\StteacherMkexperienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Stteacher Mkexperiences');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stteacher-mkexperience-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="btn-group-container">
        <?= Html::a(Yii::t('substituteteacher', 'Create Stteacher Mkexperience'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= ButtonDropdown::widget([
            'label' => Yii::t('substituteteacher', 'Batch Import MK Experiences'),
            'options' => ['class' => 'btn-primary'],
            'dropdown' => [
                'items' => [
                    [
                        'label' => Yii::t('substituteteacher', 'Import Experiences'),
                        'url' => ['substitute-teacher-file/import', 'route' => 'import/file-information', 'type' => 'stteacher-mkexperience'],
                    ],
                    [
                        'label' => Yii::t('substituteteacher', 'Download import sample'),
                        'url' => "{$bundle->baseUrl}/ΥΠΟΔΕΙΓΜΑ ΜΑΖΙΚΗΣ ΕΙΣΑΓΩΓΗΣ ΠΡΟΫΠΗΡΕΣΙΩΝ ΑΝΑΠΛΗΡΩΤΩΝ ΕΤΟΥΣ.xls"
                    ],
                ],
            ],
        ]);
        ?>        
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'teacher_id',
                'value' => function ($m) {
                    return $m->teacher ? $m->teacher->name . ' (Κωδ.:'. $m->teacher->id .')' : null;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'teacher_id',
                    'data' => Teacher::defaultSelectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],            
            //'teacher_id',
            'exp_startdate',
            'exp_enddate',
            'exp_years',
            'exp_months',
            'exp_days',
            'exp_sectorname',
            
            [ 
                'attribute' => 'exp_sectortype', 
                'value' => 'exp_sectortype_label',
                'label' => Yii::t('substituteteacher', 'Exp SectorType'),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'exp_sectortype',
                    'data' => \app\modules\SubstituteTeacher\models\StteacherMkexperience::getExpSectorType(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],                
            ])],
                        
              
            //'exp_sectortype',
            //'exp_info',
            [
                'attribute' => 'exp_mkvalid',
                'value' => 'exp_mkvalid_label',
                'label' => Yii::t('substituteteacher', 'Exp Mkvalid'),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'exp_mkvalid',
                    'data' => \app\modules\SubstituteTeacher\models\StteacherMkexperience::getExpValidity(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
