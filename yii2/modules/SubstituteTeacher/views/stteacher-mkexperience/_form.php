<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\SubstituteTeacher\models\StteacherMkexperience;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\StteacherMkexperience */
/* @var $form yii\widgets\ActiveForm */
$exp_js = '$(document).ready(function(){
                    $("#startdate, #enddate, #hours, #hpweek").change(function(){
                        var sdate = document.getElementById("startdate").value;
                        var edate = document.getElementById("enddate").value;
                        var hours= document.getElementById("hours").value;
                        var factor;
                        if (hours==0) {
                            document.getElementById("hpweek").disabled = true;
                            document.getElementById("hpweek").value="";
                            factor=1;
                        } else if (hours==1) {
                            document.getElementById("hpweek").disabled = false;
                            factor= document.getElementById("hpweek").value / 40;
                            if (factor==0) {factor=1;}
                        } else {
                            document.getElementById("hpweek").disabled = false;
                            factor= 0;
                        }
                        //alert(factor);
                        var sy, sm, sd, ey, em, ed, dif,mdif,lmd;
                        
                        if (sdate != "" && edate != "") {
                            sy  = parseInt(sdate.substring(0,4));
                            sm  = parseInt(sdate.substring(5,7));
                            sd  = parseInt(sdate.substring(8,10));                    
                            ey  = parseInt(edate.substring(0,4));
                            em  = parseInt(edate.substring(5,7));
                            ed  = parseInt(edate.substring(8,10));                    
                            mdif = (ey-sy)*12 + (em-sm);
                            if (em==2 && ((ey % 4 == 0 && ey %100 !=0) || ey % 400 == 0)) { if (ed==28) ed=30; }
                            if (mdif >=0) {
                                dif = (mdif-1)*30 + (31 - sd) + Math.min(ed,30)
                            }
                            if (dif < 0) {dif=0;}
                            dif = Math.round( dif * factor );
                            //alert(dif);
                            //dif = ((ey-sy)*12 + (em-sm))*30 + Math.min(ed,30) - sd + 1;
                            //alert(parseInt(dif / 360) + "-" + parseInt((dif % 360)/30) + "-" + (dif % 30));
                            document.getElementById("expy").value = parseInt(dif / 360);
                            document.getElementById("expm").value = parseInt((dif % 360)/30);
                            document.getElementById("expd").value = dif % 30;                            
                        } 
                    });

                });';

$this->registerJs($exp_js, View::POS_END);

?>

<div class="stteacher-mkexperience-form">

    <?php $form = ActiveForm::begin(); ?>

   
    
    <?=
      $form->field($model, 'teacher_id')->widget(Select2::classname(), [
      'data' => \app\modules\SubstituteTeacher\models\Teacher::defaultSelectables(),
      'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
      'pluginOptions' => [
      'allowClear' => false
       ],
      ]);
    ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'exp_hours')->dropDownList(StteacherMkexperience::getExpHours(), ['prompt' => Yii::t('substituteteacher', 'Choose...'), 'id'=>'hours'])  ?>
        </div>       
        <div class="col-md-2">
            <?= $form->field($model, 'exp_hourspweek')->textInput(['type' => 'number', 'min'=>'1', 'disabled' =>true, 'id' => 'hpweek']) ?>
        </div>             
        <div class="col-md-4">
            <?= $form->field($model, 'exp_startdate')->widget(DateControl::classname(), 
                    ['type'=>DateControl::FORMAT_DATE,
                     'options' => ['id'=>'startdate']
                    ]);?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'exp_enddate')->widget(DateControl::classname(), 
                    ['type'=>DateControl::FORMAT_DATE,
                     'options' => ['id'=>'enddate']
                    ]);?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'exp_years')->textInput(['type' => 'number', 'id'=>'expy']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'exp_months')->textInput(['type' => 'number', 'id'=>'expm']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'exp_days')->textInput(['type' => 'number', 'id'=>'expd']) ?>
        </div>       
    </div>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'exp_sectorname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'exp_sectortype')->dropDownList(StteacherMkexperience::getExpSectorType(), ['prompt' => Yii::t('substituteteacher', 'Choose...')])  ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'exp_mkvalid')->dropDownList(StteacherMkexperience::getExpValidity(), ['prompt' => Yii::t('substituteteacher', 'Choose...')])  ?>
        </div>       
    </div>


    <?= $form->field($model, 'exp_info')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
