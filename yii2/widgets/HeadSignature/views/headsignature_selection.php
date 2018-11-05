<?php

use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;

$ajaxscript_SetWhoSigns = 'function setWhoSigns(url){
                                    var who_signs_selection = document.getElementById("whosigns_frmid").value;
                                    alert(who_signs_selection); return;
    
                                    $.ajax({
                                            url: url,
                                            type: "post",
                                            data: {"working_module":localdirdecision_protocol, "localdirdecision_directorate":localdirdecision_directorate},
                                            success: function(data){
                                                        data = JSON.parse(data);
                                                        if(data == null) {
                                                            $("#localdirdecision_action_frmid").prop("disabled", false);
                                                            $("#localdirdecision_subject_frmid").prop("disabled", false);
                                                            $("#localdirdecision_action_frmid").val("").trigger("change");
                                                            $("#localdirdecision_subject_frmid").val("").trigger("change");
                                                        }
                                                        else {
                                                            $("#localdirdecision_subject_frmid").val(data.localdirdecision_subject).trigger("change");
                                                            $("#localdirdecision_action_frmid").val(data.localdirdecision_action).trigger("change");
                                                            $("#localdirdecision_action_frmid").prop("disabled", true);
                                                            $("#localdirdecision_subject_frmid").prop("disabled", true);
                                                        }
                                                     },
                                            error: function(){alert("Hallo");}
    
                        	               })
                                }';


$this->registerJs($ajaxscript_SetWhoSigns, View::POS_HEAD);
$url_setWhoSigns = Url::to('/disposal/disposal/getteacher-ajax');

//echo "<pre>"; print_r($model); echo "</pre>"; die();
?>

<div class="row">
	<div class="col-lg-6"></div>
	<div class="col-lg-6">
    	<?= $form->field($model, 'who_signs')->widget(Select2::classname(), [
                            'data' => $head_signs,
    	    'options' => ['id' => 'whosigns_frmid', 'placeholder' => Yii::t('app', 'Επιλέξτε Υπογράφων...'), 'onchange' => 'setWhoSigns("' . $url_setWhoSigns .'");'],
                                ])->label('Υπογράφων:'); ?>
    </div>
        <?php
/*         echo Select2::widget([
            'name' => 'state_10',
            'data' => $head_signs,
            'options' => [
                'placeholder' => 'Επιλέξτε Υπογράφων ...',
                'multiple' => false
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); */
    ?>
</div>