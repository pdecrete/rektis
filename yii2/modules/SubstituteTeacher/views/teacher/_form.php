<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\modules\SubstituteTeacher\models\Prefecture;
use app\modules\SubstituteTeacher\models\PlacementPreference;
use kartik\select2\Select2;
use dosamigos\switchinput\SwitchBox;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use kartik\datecontrol\DateControl;
use yii\web\View;
//use yii\helpers\Html; 

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-serial-number").each(function(index) {
        jQuery(this).html("" + (index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-serial-number").each(function(index) {
        jQuery(this).html("" + (index + 1))
    });
});

var expconvert = function (value) {
    var days = value % 30;
    var months_rem = parseInt((value - days) / 30);
    var months = months_rem % 12;
    var years = parseInt(months_rem / 12);
    return "" + years + "E " + months + "M " + days + "H";
};

var expdisplay = function (elem) {
    var value = parseInt(elem.val()); 
    if (value > 0) {
        elem.next(".hinter").html(expconvert(value));
    } else {
        elem.next(".hinter").html("...");
    }
}

$(".expconv").each(function (idx) {
    expdisplay($(this));
});

$(".expconv").on("change paste keyup", function() {
    expdisplay($(this));
});

';

$mk_js = '$(document).ready(function(){
                           
                    $("#edate,#edate, #tdate, #tyears, #yearsper").change(function(){
                        var changed=0;
                        var x = 0;
                        var ey = parseInt(document.getElementById("eyears").value);
                        var em = parseInt(document.getElementById("emonths").value);
                        var ed = parseInt(document.getElementById("edays").value);
                        var edt = document.getElementById("edate").value;
                        var tdt = document.getElementById("tdate").value;
                        var ty = parseInt(document.getElementById("tyears").value);
                        var yp = parseInt(document.getElementById("yearsper").value);                        
                        var today = Date.now();
                        //alert("-"+ey+"-"); alert("-"+ed+"-");alert("-"+td+"-");alert("-"+ty+"-");alert("-"+yp+"-"); alert(document.getElementById("mkid").value);
                        if (tdt=="") ty=0;
                        if (edt=="") {
                                    ey=0;em=0;ed=0; 
                                    document.getElementById("mkchangedate").value =  null;
                                    document.getElementById("mkid").value = 1 + parseInt((ty + x/360)/yp) + changed;   
                        } else {
                            var parts = edt.split("-");
                            var edate = new Date(parts[0], parts[1] - 1, parts[2]);
                            
                            var y,j, diff;
                            
                            //diff = today - edate;
                            
                            //diff = Math.round(diff/(24*3600*1000));
                            //alert ("--"+edate+"--"+today+"--"+diff);
                            x = ey*360+em*30+ed;
                            if (yp==2) { y = 720;}
                            else {y=1080;}
                            j=y;
                            while (j<x) { j = j + y; }
                            diff = j - x;
                            //alert ("--"+edate.getTime()+"--");
                            //alert ("--"+edate+"--"+today+"--"+diff+"--"+x+"--"+yp+"--"+y+"--"+j+"--"+ey);
                            
                            //simple method to calculate estimated date of changing mk
                            //var calcdate = new Date(edate.getTime() + diff*24*3600*1000);

                            //complex method to calculate estimated date of changing mk by 30day month estimation
                            var diffy = parseInt(diff / 360);
                            var diffm = parseInt((diff - diffy*360) / 30);
                            var diffd = diff - diffy*360 - diffm*30;         
                            var t1 = edate.getDate() + diffd;
                            var cd = t1 % 30;    
//                            alert("t1="+t1);
//                            alert("cd="+cd);
                            var t2 = (parseInt(t1 / 30) + edate.getMonth() + 1 + diffm);
                            var cm = t2 % 12;  
//                            alert("t2="+t2);
//                            alert("cm="+cm);                            
                            var cy = edate.getFullYear() + parseInt(t2  / 12 ) + diffy;   
//                            if (cm==2 && (cd==29 || cd==30)) { cm=2;cd=28;}
                            var calcdate = new Date(cy,cm-1,cd,0,0,0,0);

                            changed=0;
                            if (calcdate < today) { 
                                //diff2 = Math.round((today - calcdate)/(24*3600*1000));
                                changed = 1;
                                //calcdatenew = new Date(calcdate.getTime() + y*24*3600*1000);
                                calcdatenew = new Date(cy+yp,cm-1,cd,0,0,0,0);
                                //alert (diff2);
                                calcdate = calcdatenew;
                            }
                            var calcdatestr = calcdate.getFullYear() + "-" + (calcdate.getMonth() + 1) + "-" +  calcdate.getDate() ;
                            document.getElementById("mkchangedate").value =  calcdatestr;
                            //document.getElementById("mkchangedate").datepicker("update", calcdatestr);
                            //document.getElementById("mkchangedate").value =  calcdate.getDate() + "/" + (calcdate.getMonth() + 1) + "/" + calcdate.getFullYear() ;
                            document.getElementById("mkid").value = 1 + parseInt((ty + x/360)/yp) + changed;                     
                        }
                    });
                    
                });';

$this->registerJs($js);
//$this->registerJs($mk_js, View::POS_HEAD);
$this->registerJs($mk_js, View::POS_END);

$firstModelPlacementPreference = reset($modelsPlacementPreferences);
?>

<div class="teacher-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="row">
        <div class="col-md-9">
            <?=
        $form->field($model, 'registry_id')->widget(Select2::classname(), [
            'data' => TeacherRegistry::defaultSelectables(),
            'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
            'pluginOptions' => [
                'multiple' => false,
                'allowClear' => true
            ],
        ]);
        ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'year')->textInput(['type' => 'number']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'public_experience')->textInput(['type' => 'number', 'class' => 'form-control expconv'])->hint('...', ['class' => 'hinter']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'smeae_keddy_experience')->textInput(['type' => 'number', 'class' => 'form-control expconv'])->hint('...', ['class' => 'hinter']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'disability_percentage')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'disabled_children')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'many_children')->widget(SwitchBox::className(), [
                'options' => [
                    'label' => '',
                ],
                'clientOptions' => [
                    'size' => 'small',
                    'onColor' => 'success',
                    'onText' => Yii::t('substituteteacher', 'YES'),
                    'offText' => Yii::t('substituteteacher', 'No'),
                ]
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'three_children')->widget(SwitchBox::className(), [
                'options' => [
                    'label' => '',
                ],
                'clientOptions' => [
                    'size' => 'small',
                    'onColor' => 'success',
                    'onText' => Yii::t('substituteteacher', 'YES'),
                    'offText' => Yii::t('substituteteacher', 'No'),
                ]
            ]);
            ?>
        </div>
    </div>

    <h3><b> Μισθολογικά Στοιχεία </b></h3>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'mk_years')->textInput(['readonly' =>true, 'id' => 'eyears'])?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'mk_months')->textInput(['readonly' =>true, 'id' => 'emonths'])?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'mk_days')->textInput(['readonly' =>true, 'id' => 'edays'])?>
        </div>        
        <div class="col-md-2">
            <?= $form->field($model, 'mk_exptotdays')->textInput(['readonly' =>true])?>
        </div>        
        <div class="col-md-4">
            <?= $form->field($model, 'mk_appdate')->widget(DateControl::classname(),[
                                    'type'=>DateControl::FORMAT_DATE,
                                    'options' => ['id' => 'edate']
                                    ] );?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'mk_titleyears')->dropDownList(Teacher::getMkTitleYears(), 
                                            ['prompt' => Yii::t('substituteteacher', 'Choose...'),
                                             'id' => 'tyears',
                                             //'onchange'=>'mkdisplay()'   
                                            ])  ?>
        </div>               
        <div class="col-md-4">
            <?= $form->field($model, 'mk_titleappdate')->widget(DateControl::classname(), [
                                        'type'=>DateControl::FORMAT_DATE,
                                    'options' => ['id' => 'tdate']
                                    ] );?>                    
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'mk_yearsper')->dropDownList(Teacher::getMkCateg(), 
                                            ['prompt' => Yii::t('substituteteacher', 'Choose...'),
                                            'id' => 'yearsper',  
                                            //'onchange'=>'mkdisplay()'       
                                            ])  ?>
        </div>               
        <div class="col-md-2">
            <?= $form->field($model, 'mk')->textInput(['readonly' =>true, 'id' => 'mkid']) ?>
        </div>            
    </div>    

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'mk_titleinfo')->textInput() ?>
        </div>
        <!--
        <div class="col-md-4">
            <?= $form->field($model, 'mk_changedate')->widget(DateControl::classname(), [
                                        'type'=>DateControl::FORMAT_DATE,
                                        'disabled' =>true,     
                                        'options' => ['id' => 'mkchangedate']
                                    ] );?>                    
        </div> 
        -->
        
        <div class="col-md-4">
            <?= $form->field($model, 'mk_changedate')->textInput(['readonly' =>true, 'id' => 'mkchangedate']) ?>
        </div>
    </div>    
    <div class="row">
        <div class="col-sm-6">
            <?=
            $form->field($model, 'operation_descr')->textInput()
            ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'sector')->dropDownList(Teacher::getSectors(), ['prompt' => Yii::t('substituteteacher', 'Choose...')])  ?>
        </div>
        
    </div>    
    
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3><b><?php echo Yii::t('substituteteacher', 'Teacher boards'); ?> </b></h3>
        </div>
        <div class="panel-body">
            <?php if (!isset($modelsBoards)) : ?>
            <p>Μπορείτε να προσθέσετε στοιχεία πινάκων διορισμών
                <strong>μετά την δημιουργία</strong>.</p>
            <?php elseif (empty($modelsBoards)) : ?>
            <p>Δεν υπάρχουν στοιχεία.</p>
            <?php else: ?>
            <?php 
            $firstModelBoard = reset($modelsBoards);
            ?>
            <table class="table table-striped table-hover">
                <caption>Εάν ο καθηγητής δεν υπάγεται σε πίνακα κάποιας ειδικότητας, εισάγετε κενό στην αντίστοιχη σειρά κατάταξης
                    στον πίνακα και στα μόρια και αφαιρέστε την επιλογή τύπου πίνακα διορισμού.</caption>
                <thead>
                    <tr>
                        <th>
                            <?php echo Yii::t('substituteteacher', 'Order in board'); ?>
                        </th>
                        <th>
                            <?php echo $firstModelBoard->getAttributeLabel('board_type'); ?>
                        </th>
                        <th>
                            <?php echo Yii::t('substituteteacher', 'Specialisation'); ?>
                        </th>
                        <th>
                            <?php echo $firstModelBoard->getAttributeLabel('points'); ?>
                        </th>
                        <th>
                            <?php echo Yii::t('substituteteacher', 'Status'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modelsBoards as $index => $modelBoard): ?>
                    <tr class="item">
                        <td>
                            <?php
                                // necessary for update action.
                                if (!$modelBoard->isNewRecord) {
                                    echo Html::activeHiddenInput($modelBoard, "[{$index}]id");
                                }
                            ?>
                            <?= $form->field($modelBoard, "[{$index}]order")->textInput(['type' => 'number', 'min' => 0])->label(false) ?>
                            <?php 
                                $teacher_errors = $modelBoard->getErrors('teacher_id');
                                if (!empty($teacher_errors)) :
                            ?>
                            <div class="text-danger">
                                <?= implode(', ', $teacher_errors) ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $form->field($modelBoard, "[{$index}]board_type")->dropDownList(TeacherBoard::getChoices('board_type'), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>
                        </td>
                        <td>
                            <?php echo $modelBoard->specialisation->code; ?>
                            <?= $form->field($modelBoard, "[{$index}]specialisation_id")->hiddenInput()->label(false) ?>
                        </td>
                        <td class="col-sm-2">
                            <?= $form->field($modelBoard, "[{$index}]points")->textInput()->label(false) ?>
                        </td>
                        <td class="col-sm-2">
                            <?= $form->field($modelBoard, "[{$index}]status")->dropDownList(Teacher::getChoices('status'), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <?php 
    DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $firstModelPlacementPreference,
        'formId' => 'dynamic-form',
        'formFields' => [
            'prefecture_id',
            'school_type',
            'order'
        ],
    ]);
    ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3><b><?php echo Yii::t('substituteteacher', 'Placement preferences'); ?></b></h3>
            <button type="button" class="add-item btn btn-success btn-xs">
                <span class="glyphicon glyphicon-plus"></span>
                <?php echo Yii::t('substituteteacher', 'Add new preference'); ?>
            </button>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <!-- widgetContainer -->
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="col-xs-1">#</th>
                        <th>
                            <?php echo $firstModelPlacementPreference->getAttributeLabel('prefecture_id'); ?>
                        </th>
                        <th>
                            <?php echo $firstModelPlacementPreference->getAttributeLabel('school_type'); ?>
                        </th>
                        <th>
                            <?php echo $firstModelPlacementPreference->getAttributeLabel('order'); ?>
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="container-items">
                    <?php foreach ($modelsPlacementPreferences as $index => $modelPlacementPreference): ?>
                    <tr class="item">
                        <td>
                            <span class="badge panel-serial-number">
                                <?php echo $index + 1; ?>
                            </span>
                        </td>
                        <td>
                            <?php
                                // necessary for update action.
                                if (!$modelPlacementPreference->isNewRecord) {
                                    echo Html::activeHiddenInput($modelPlacementPreference, "[{$index}]id");
                                }
                            ?>
                            <?= $form->field($modelPlacementPreference, "[{$index}]prefecture_id")->dropDownList(Prefecture::defaultSelectables(), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>
                            <?php 
                                $teacher_errors = $modelPlacementPreference->getErrors('teacher_id');
                                if (!empty($teacher_errors)) :
                            ?>
                            <div class="text-danger">
                                <?= implode(', ', $teacher_errors) ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $form->field($modelPlacementPreference, "[{$index}]school_type")->dropDownList(PlacementPreference::getChoices('school_type'), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>
                        </td>
                        <td class="col-sm-2">
                            <?= $form->field($modelPlacementPreference, "[{$index}]order")->textInput(['type' => 'number', 'min' => 0])->label(false) ?>
                        </td>
                        <td class="col-sm-1 text-center">
                            <button type="button" class="remove-item btn btn-danger btn-sm">
                                <span class="glyphicon glyphicon-minus"></span>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('substituteteacher', 'Create') : Yii::t('substituteteacher', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>