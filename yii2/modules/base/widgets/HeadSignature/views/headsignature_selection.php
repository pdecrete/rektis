<?php

use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;

$ajaxscript_SetWhoSigns = 'function setWhoSigns(url, module){
                                    var who_signs_selection = document.getElementById("whosigns_frmid").value;
                                    var who_signs_changemessage = document.getElementById("whosigns_changemessage");                                    
                                    $.ajax({
                                            url: url,
                                            type: "post",
                                            data: {"working_module":module, "who_signs":who_signs_selection},
                                            success: function(data){                 
                                                        $("#whosigns_changemessage").html("<strong><font color=\"green\">Ο υπογράφων άλλαξε επιτυχώς.</font></strong>");                                       
                                                        $("#whosigns_changemessage").fadeIn();
                                                        who_signs_changemessage.className = "pull-right";
                                                        $("#whosigns_changemessage").delay(3000).fadeOut("slow"); 
                                                        data = JSON.parse(data); 
                                                        if(data == null) {
                                                           
                                                        }
                                                        else {
                                                            
                                                        }
                                                     },
                                            error: function(){
                                                        $("#whosigns_changemessage").html("<strong><font color=\"red\">Σφάλμα στην αλλαγή του υπογράφοντος.</font></strong>");                                       
                                                        $("#whosigns_changemessage").fadeIn();
                                                        who_signs_changemessage.className = "pull-right";
                                                        $("#whosigns_changemessage").delay(3000).fadeOut("slow"); 
                                                     } 
                        	               })
                                }';


$this->registerJs($ajaxscript_SetWhoSigns, View::POS_HEAD);
$url_setWhoSigns = Url::toRoute(['/head-signature/signatureajax']);
?>

<div class="row">
	<div class="col-lg-6"></div>
	<div class="col-lg-6">
    	<?= $form->field($model, 'who_signs')->widget(Select2::classname(), [
                                                        'data' => $head_signs,
                                                        'options' => ['id' => 'whosigns_frmid', 'placeholder' => Yii::t('app', 'Επιλέξτε Υπογράφων...'), 'onchange' => 'setWhoSigns("' . $url_setWhoSigns .'", "' . $module . '");'],
                                                        ])->label('Υπογράφων:'); 
    	?><span id="whosigns_changemessage" class="hidden pull-right"></span>
    </div>
</div>