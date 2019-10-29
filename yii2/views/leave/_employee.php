<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\LeaveType;
use app\modules\eduinventory\components\EduinventoryHelper;
use app\models\Employee;
use app\modules\eduinventory\EducationInventoryModule;
?>
<div class="leave-index">
    <h1>Ιστορικό αδειών <small>Σύνολο μη διεγραμμένων: <?= $employeeModel->leavesDuration; ?> ημέρες</small></h1>
    <p>Διεγραμμένες άδειες επισημαίνονται με <span class="bg-danger">κόκκινο χρώμα</span>.</p>
    <?php //Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $key, $index, $grid) {
            if ($model->deleted) {
                return ['class' => 'danger'];
            }
        },
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'type',
                        'value' => 'typeObj.name',
                    ],
                    [
                        'attribute' => 'duration',
                        'value' => function ($model) {
                            return Yii::t('app', '{days} days, from {start_date} to {end_date}', [
                                        'days' => $model->duration,
                                        'start_date' => \Yii::$app->formatter->asDate($model->start_date),
                                        'end_date' => \Yii::$app->formatter->asDate($model->end_date)
                            ]);
                        },
                    ],
                    [   'attribute' => 'decision_protocol',
                        'header' => '<span class="text-wrap">Πρωτόκολλο<br/>Απόφασης</span>',
                    ],
                        'decision_protocol_date:date',
                    [
                        'attribute' => 'Έτος',
                        'format' => 'html',
                        'value' => function($model) {
                                      if(LeaveType::isSchoolYearBased($model['type'])) {
                                          $year = EduinventoryHelper::getSchoolYearOf($model['start_date']);
                                          return $year . "-" . ($year+1) . "<br>(Σχολ. έτος)";
                                      }
                                      else {
                                          $date = DateTime::createFromFormat("Y-m-d", $model['start_date']);
                                          return $date->format("Y") . "<br>(Ημερολογ. έτος)";                               
                                      }
                                   }
                    ],
                    [
                        'attribute' => 'Total Leaves',
                        'header' => '<span class="text-wrap">Σύνολο Αδειών<br/>(αυτού του<br>τύπου και έτους)</span>',
                        'format' => 'html',
                        'value' => function($model) {
                        if(LeaveType::isSchoolYearBased($model['type'])) {
                            $year = EduinventoryHelper::getSchoolYearOf($model['start_date']);
                            $startdate = $year . '-09-01';
                            $enddate = ($year+1). '-08-31';
                        }
                        else {
                            $date = DateTime::createFromFormat("Y-m-d", $model['start_date']);
                            $year = $date->format("Y");
                            $startdate = $year . '-01-01';
                            $enddate = $year . '-12-31';
                        }
                        return Employee::getTotalLeavesDuration($model['employee'], $model['type'], $startdate, $enddate);
                        }
                    ],
                    [
                        'attribute' => 'Limit',
                        'header' => '<span class="text-wrap">Όριο<br/>Έτους</span>',
                        'format' => 'html',
                        'value' => function($model) {
                            return LeaveType::find()->where(['id' => $model['type']])->one()['limit'];
                        }
                    ],
                    [
                        'attribute' => 'Balance',
                        'header' => '<span class="text-wrap">Υπόλοιπο<br/>Προηγούμενου<br/>Έτους</span>',
                        'format' => 'html',
                        'value' => function($model) { //echo "<pre>"; print_r($model); echo "<pre>"; die();                                                                                
                                        if(LeaveType::isSchoolYearBased($model['type'])) {
                                            return "-";
                                        }
                                        else {
                                            $date = DateTime::createFromFormat("Y-m-d", $model['start_date']);
                                            $year = $date->format("Y");
                                            return Employee::getLeaveTypeBalance($model['employee'], $model['type'], $year-1);
                                        }
                                    }
                    ],
                    [
                        'attribute' => 'Απομένουν',
                        'format' => 'html',
                        'value' => function($model) { //echo "<pre>"; print_r($model); echo "<pre>"; die();     
                            if(LeaveType::isSchoolYearBased($model['type'])) {
                                $year = EduinventoryHelper::getSchoolYearOf($model['start_date']);
                            }
                            else {
                                $date = DateTime::createFromFormat("Y-m-d", $model['start_date']);
                                $year = $date->format("Y");
                            }
                            return Employee::getLeavesRemaingDays($model['employee'], $model['type'], $year);
                        }
                    ],
                            // 'application_protocol',
                            // 'application_protocol_date',
                            // 'application_date',
                            // 'accompanying_document',
                            // 'reason',
                            // 'comment:ntext',
                            // 'create_ts',
                            // 'update_ts',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}',
                                'urlCreator' => function ($action, $model, $key, $index) {
                                    return Url::to(["/leave/{$action}", 'id' => $model->id]);
                                },
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return $model->deleted ? '' : Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                            'title' => Yii::t('yii', 'View'),
                                                            'data-pjax' => '0',
                                                ]);
                                            },
                                                    'download' => function ($url, $model, $key) {
                                                return $model->deleted ? '' : Html::a(
                                                                '<span class="glyphicon glyphicon-download"></span>', $url, [
                                                            'title' => Yii::t('app', 'Download'),
                                                            'data-confirm' => Yii::t('yii', 'Are you sure you want to download this leave?'),
                                                            'data-method' => 'post',
                                                            'data-pjax' => '0',
                                                                ]
                                                );
                                            }
                                                ]
                                            ],
                                        ],
                                    ]);
                                    ?>
                                    <?php //Pjax::end(); ?>
</div>
