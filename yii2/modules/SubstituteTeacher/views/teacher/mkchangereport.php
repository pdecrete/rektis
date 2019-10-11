
<?php

//use app\modules\finance\Yii;
//use app\modules\finance\components\Money;

//foreach ($models as $model)
//echo "<pre>"; print_r($model['DEDUCTIONS']); echo "</pre>"; die();
//echo "<pre>"; print_r($models); echo "</pre>"; die();

$inline_th_css = 'style="text-align: center;border: 1px solid black;font-weight:bold;word-wrap:break-word;"';
$inline_th_css_min_width = 'style="text-align: center;border: 1px solid black;font-weight:bold;word-wrap:break-word;width:0px;"';
$inline_td_css_right = 'style="text-align: right;border: 1px solid black;"';
$inline_td_css_left = 'style="text-align: left;border: 1px solid black;"';
/*
$sum_net_value = 0;
$sum_vat = 0;
$sum_taxes = 0;
$sum_payable_amount = 0;
$sum_expenditure_taxes = 0;

$deductions_array = [];
$deductions_array_sum = [];
$maxnum_deductions = 0;

$show_notes_column = false;
*/

/*
foreach ($mkteachers as $mkteacher) {
          ++$i;
          $registry_model = TeacherRegistry::findOne(['id' => $mkteacher->registry_id]);
          $mkchanges_array[$i]["fullName"] = $registry_model->surname." ".$registry_model->firstname;            
          $mkchanges_array[$i]["fpecialty"] = $registry_model->specialisation_labels;
          $mkchanges_array[$i]["mk"] = $mkteacher->mk;
          $mkchanges_array[$i]["appdate"] = $mkteacher->mk_appdate;
          $mkchanges_array[$i]["mkchangedate"] = $mkteacher->mk_changedate;
}
*/
$greek_logo = "file:///" . realpath(Yii::getAlias('@images/greek_logo.png'));
?>
<div class="teacher-mkchangereport">
    <table style="border: 0px; padding: 5 5 5 5px;">
    	<tr><td colspan="2" style="text-align:center"><?= '<img src=' . $greek_logo . '>' ?><h5><strong><?= Yii::$app->params['pde_logo_literal']; ?><br />
			<?= Yii::$app->params['personel_logo_literal']; ?></strong></h5></td><td></td></tr>
	</table>
	<!--p><?= '<img src=' . $greek_logo . '>' ?></p-->
   <p><strong><?= Yii::t('substituteteacher', 'MK CHANGE REPORT') ?> </strong> </p>
    <!--
    <p><strong><?= Yii::t('app', 'For the needs of the Regional Directorate of Primary & Secondary Education of Crete') ?></strong></p>
    -->
	<table style="width:100%;border-collapse: collapse;">
		<tr>
			<td <?= $inline_th_css?>><?= Yii::t('substituteteacher', 'Teacher') ?></td>
			<td <?= $inline_th_css?>><?= Yii::t('app', 'Father\'s name') ?></td>
			<td <?= $inline_th_css?>><?= Yii::t('app', 'Mother\'s name') ?></td>
			<td <?= $inline_th_css?>><?= Yii::t('app', 'Specialisation') ?></td>
			<td <?= $inline_th_css?>><?= Yii::t('substituteteacher', 'MK') ?></td>
			<td <?= $inline_th_css?>><?= Yii::t('substituteteacher', 'Exp Appdate') ?></td>
			<td <?= $inline_th_css?>><?= Yii::t('substituteteacher', 'MK Changedate') ?></td>												
		</tr>
		<?php   foreach ($mkteacherarr as $mkteacher):
                        ?>
            		<tr>
            			<td <?= $inline_td_css_left?>><?= $mkteacher['fullname']; ?></td>
            			<td <?= $inline_td_css_left?>><?= $mkteacher['fathername']; ?></td>
            			<td <?= $inline_td_css_left?>><?= $mkteacher['mothername']; ?></td>
                                <td <?= $inline_td_css_right?>><?= $mkteacher['specialty'][0] ?></td>
            			<td <?= $inline_td_css_right?>><?= $mkteacher['mk']; ?></td>
                                <td <?= $inline_td_css_right?>><?= $mkteacher['mk_appdate']; ?></td>
            			<td <?= $inline_td_css_right?>><?= $mkteacher['mk_changedate']; ?></td>
                        </tr>
                                  
		<?php endforeach;?>
	</table>
	<br /><br />
	<table style="width:100%;border: 0;">
		<tr>
			<td style="text-align:right;width:25%;border: 0;">&nbsp;</td>
			<td style="text-align:right;width:25%;border: 0;">&nbsp;</td>
			<td style="text-align:center;width:50%;border: 0;">
				<p>ΒΕΒΑΙΩΝΕΤΑΙ Η ΑΚΡΙΒΕΙΑ ΤΩΝ ΠΑΡΑΠΑΝΩ</p>
				<p>&nbsp;</p>
				<p><strong><?= Yii::$app->params['director_sign'] ?></strong><br /></p>
				<p><strong><?= Yii::$app->params['director_name']; ?></strong></p>
			</td>			
	</table>
</div>

