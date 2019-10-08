<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\FilterActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\OperationSpecialisationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Operation Specialisations');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="operation-specialisation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Create Operation Specialisation'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'operation_id',
                'value' => function ($m) {
                    return $m->operation ? $m->operation_id . " : " . $m->operation->title . ' ' . $m->operation->year : $m->operation_id;
                }
            ],
            [
                'attribute' => 'specialisation_id',
                'value' => function ($m) {
                    return $m->specialisation ? $m->specialisation_id . " : " . $m->specialisation->code . ' ' . $m->specialisation->name : $m->specialisation_id;
                
                }
            ],
            [
                'class' => FilterActionColumn::className(),
                'filter' => FilterActionColumn::LINK_INDEX_CONFIRM,
                'template' => '{update} {delete}'
            ],
        ],
    ]);

    ?>
</div>
