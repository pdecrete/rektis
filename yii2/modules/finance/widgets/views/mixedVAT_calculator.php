<?php
use yii\web\View;

$ajaxscript =   'function calculate_mixed_vat() {                                            
                     beforeVatAmount_value = parseFloat(document.getElementById("beforeVATamount").value);
                     amount1_value = parseFloat(document.getElementById("amount1").value);                                            
                     document.getElementById("amount2").value = beforeVatAmount_value - amount1_value;
                     amount2_value = parseFloat(document.getElementById("amount2").value);
                     vat1 = parseFloat(document.getElementById("VAT1").value/100);
                     vat2 = parseFloat(document.getElementById("VAT2").value/100);
                     document.getElementById("afterVATamount").value = amount1_value*(1+vat1) + amount2_value*(1+vat2);
                     checkAmounts(amount1_value, amount2_value, vat1, vat2);
                 }

                 function checkAmounts(amount1, amount2, vat1, vat2) {
                     if(amount1 < 0 || amount2 < 0 || vat1 < 0 || vat2 < 0)
                        document.getElementById("errorMessage").innerHTML = "Υπάρχει αρνητικό ποσό ή ΦΠΑ. Παρακαλώ ελέγξτε!";
                     else                                                          
                        document.getElementById("errorMessage").innerHTML = "";
                 }

                 function copyAfterVATAmount() {
                     document.getElementById("afterVATamount").select();
                     document.execCommand("copy");
                 } 
                ';

$this->registerJs($ajaxscript, View::POS_END);
?>

<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#mixedVatCalculator">Υπολογισμός Μικτού ΦΠΑ</button>
<form onsubmit="return false;">
    <div id="mixedVatCalculator" class="collapse"><br />
        <div class="form-group">        
            <div class="row">
                <div class="col-lg-5"><label>Ποσό προ μικτού ΦΠΑ:</label></div>            
                <div class="col-lg-4"><label>Ποσό 1</label></div>
                <div class="col-lg-3"><label>ΦΠΑ 1 (%)</label></div>
            </div>
            <div class="row">
                <div class="col-lg-5"><input id="beforeVATamount" type="number" min="0.00" step="0.1" oninput="calculate_mixed_vat()" value="0" class="form-control" /></div>            
                <div class="col-lg-4"><input id="amount1" type="number" min="0.00" step="0.01" oninput="calculate_mixed_vat()" value="0" class="form-control" /></div>
                <div class="col-lg-3"><input id="VAT1" type="number" min="0.00" max="100" step="0.01" value="0" oninput="calculate_mixed_vat()" class="form-control" /></div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-lg-5"></div>            
                <div class="col-lg-4"><label>Ποσό 2</label></div>
                <div class="col-lg-3"><label>ΦΠΑ 2 (%)</label></div>
            </div>
            <div class="row">        	
                <div class="col-lg-5">&nbsp;</div>            
                <div class="col-lg-4"><input id="amount2" type="number" min="0.00" step="0.01" value="0" readonly class="form-control" /></div>
                <div class="col-lg-3"><input id="VAT2" type="number" min="0.00" max="100" step="0.01" value="0" oninput="calculate_mixed_vat()" class="form-control" /></div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-lg-5"><label>Ποσό μετά μικτού ΦΠΑ:</label></div>
                <div class="col-lg-4">&nbsp;</div>
                <div class="col-lg-3">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-lg-5"><input id="afterVATamount" type="number" min="0.00" value="0" readonly class="form-control" value="0" /></div>
                <div class="col-lg-4"><button class="glyphicon glyphicon-duplicate btn btn-primary btn-sm form-control" onclick="copyAfterVATAmount()">&nbsp;Αντιγραφή</button></div>
                <div class="col-lg-3"><button type="reset" class="glyphicon glyphicon-refresh btn btn-primary btn-sm form-control" onclick="document.getElementById('errorMessage').innerHTML = '';">&nbsp;Επαναφορά</button></div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-lg-12" id="errorMessage" style="font-weight: bold; color: red;"></div>
            </div>
        </div>
    </div>
</form>
<br />
<hr />