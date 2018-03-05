<?php
use app\modules\finance\components\Money;

$greek_logo = "file:///" . realpath(Yii::getAlias('@images/greek_logo.png'));
//echo "<pre>"; print_r($expstate_model); echo "</pre>";die();
?>
<table style="width: 100%; border: 0px; padding: 5 5 5 5px;">
	<tr><td colspan="2" style="text-align:center"><?= '<img src=' . $greek_logo . '>' ?><h5><strong><?= Yii::$app->params['pde_logo_literal']; ?><br />
	<?= Yii::$app->params['finance_logo_literal']; ?></strong></h5></td><td></td></tr>
    <tr><td style="text-align:right">Δ/νση:</td><td><?= Yii::$app->params['address']; ?></td>
    	<td rowspan="3" style="text-align:right">
    		<strong>
    				<?= Yii::$app->params['city']; ?>, 
    				<?= date('d/m/Y', strtotime($expstate_model['expstate_date'])); ?><br />
					Αρ. Πρωτ.: <?= $expstate_model['expstate_protocol'] ?>   
			</strong>
    	</td></tr>
	<tr><td style="text-align:right">Πληροφορίες:</td><td><?= Yii::$app->user->identity->surname . " " . Yii::$app->user->identity->name ?></td></tr>
    <tr><td style="text-align:right">Τηλ.:</td><td><?= Yii::$app->params['finance_telephone']; ?></td></tr>
    <tr><td style="text-align:right">Fax:</td><td><?= Yii::$app->params['fax']; ?></td>
    	<td rowspan="3" style="text-align:right;"><strong>Προς: ΔΥΕΕ Ηρακλείου</strong></td>
	</tr>
    <tr><td style="text-align:right">E-mail:</td><td><?= Yii::$app->params['email']; ?></td></tr>
    <tr><td style="text-align:right">Δικτυακός Τόπος:</td><td><?= Yii::$app->params['web_address']; ?></td></tr>
</table>
<br />
<h5><strong>Θέμα: "Αποστολή Δικαιολογητικών Πληρωμής"</strong></h5>
<br />
<p>
Σας στέλνουμε συνημμένα δικαιολογητικά δαπάνης που αφορούν σε <?= $expenditure_model['exp_description'] ?> της υπηρεσίας 
μας <strong>(<?= Yii::$app->params['finance_code']; ?>, KAE <?= sprintf('%04d', $kae); ?>)</strong> συνολικού ποσού: <strong><?= Money::toCurrency($expenditure_model['exp_amount'], true); ?></strong>, στο όνομα του δικαιούχου "<strong><?= $supplier_model['suppl_name'] ?></strong>" και παρακαλούμε για τις δικές σας ενέργειες. 
</p>
<p>
Ημερομηνία Έναρξης Απαίτησης: <?= date('d/m/Y', strtotime($expstate_model['expstate_date'])); ?>
</p>
<p>
Στοιχεία Δικαιούχου:<br />
ΑΦΜ: <?= $supplier_model['suppl_vat'] ?><br />
ΑΜΕ: <?= $supplier_model['suppl_employerid'] ?><br />
ΙΒΑΝ: <?= $supplier_model['suppl_iban'] ?><br />
Email: <?= $supplier_model['suppl_email'] ?><br />
</p>
<table>    
    <tr>
        <td style="width: 60%;"></td>
        <td style="width: 40%;text-align:center;">
        	<strong><?= Yii::$app->params['director_sign']; ?>
        			<br /><br /><br /><br />
        			<?= Yii::$app->params['director_name']; ?>
        	</strong>
    	</td>
    </tr>        
</table>