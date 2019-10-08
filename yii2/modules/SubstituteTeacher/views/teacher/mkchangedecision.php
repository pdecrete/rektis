
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
$c = 0;
?>
<div class="teacher-mkchangedecision">
    <table style="width:100%; border: 0px; padding: 5 5 5 5px; font:bold arial 12px">
        <!--
    	<tr><td colspan="1" style="text-align:center"><?= '<img src=' . $greek_logo . '>' ?><h5><strong><?= Yii::$app->params['pde_logo_literal']; ?><br />
			<?= Yii::$app->params['finance_logo_literal']; ?></strong></h5></td><td></td></tr>
        -->
        <tr> <td style="text-align:center;width:40%"> <?= '<img src=' . $greek_logo . '>' ?></td><td style="text-align:right"></td> </tr>
        <tr> <td style="text-align:center;width:40%"> <?= Yii::$app->params['pde_logo_literal']; ?></td><td style="text-align:right">Ηράκλειο, <?= $pd ?> <br/> Αρ.Πρωτ: <?= $pn ?></td> </tr>
        <tr> <td style="text-align:center;width:40%"> <?= Yii::$app->params['finance_logo_literal']; ?></td><td style="text-align:right">ΑΠΟΦΑΣΗ </td> </tr>
        
	</table>

    <p>
Δ/νση       : 	Κνωσσού 6<br/>
Τ.Κ.        :	71306<br/>
Πληροφορίες :	Σταυρακάκης Εμμανουήλ<br/>
Τηλ.        : 	2810302442<br/>
Fax         :	2810301103<br/>
mail        :	yoikthem@kritis.pde.sch.gr<br/>
    </p>
    <p><b>
ΘΕΜΑ: «Απόδοση προσωρινών Μισθολογικών κλιμακίων  στους   αναπληρωτές  του  Ειδικού   Εκπαιδευτικού Προσωπικού(Ε.Ε.Π) και Ειδικού Βοηθητικού Προσωπικού(ΕΒΠ)».
    </b></p>
    <p>
       O Περιφερειακός Δ/ντής Α/θμιας και Β/θμιας Εκπαίδευσης Κρήτης έχοντας υπόψη:
    </p>
    <ol>
        <li>Τις διατάξεις των άρθρων 12, 13, 14 του Ν. 1566/1985 (ΦΕΚ 167/τ. Α'/30-09-1985). </li>
        <li>Τις διατάξεις του Ν.2986/2002 (ΦΕΚ24/τ.Α’/13-2-2002 με θέμα: «Οργάνωση των Περιφερειακών Υπηρεσιών της Π/θμιας και Δ/θμιας Εκπ/σης..». </li>
        <li>Την υπ. αριθμ. Φ.351.1/11/48020/Ε3/28-3-2019 (ΑΔΑ: ΩΩΣΘ4653ΠΣ-ΒΔ3) Υπουργική Απόφαςη, με την οποία διορίστηκαν και τοποθετήθηκαν οι
        Περιφερειακοί Διευθυντές Εκπαίδευσης. </li>
        <li>Τη με αριθμ. πρωτ. οικ.2/1868/ΔΕΠ/08-02-2018 εγκφκλιο του Υπουργείου Οικονομικών με θέμα: "Άρση αναστολής της μισθολογικής εξέλιξης της παρ. 2
        του άρθρου 26 του Ν.4354/2015 </li>
        <li>Τη με αριθμ. Φ.353.1/324/105657/Δ1/16-10-2002 Υ. Α. (ΦΕΚ 1340/τ.Β΄-16-10-02), άρθρο 3 (παρ.2, εδαφ. ε), με θέμα: « Καθορισμός των ειδικότερων καθηκόντων και αρμοδιοτήτων των προϊσταμένων των περιφερειακών υπηρεσιών Π. Ε. & Δ. Ε. ….», όπως τροποποιήθηκε, συμπληρώθηκε και ισχύει σήμερα.</li>
        <li>Τις διατάξεις του άρθρου 13 του Π.Δ 1/2003(ΦΕΚ 1/τ.Α’/3-1-2003)με θέμα: «Σύνθεση, συγκρότηση και λειτουργία των υπηρεσιακών συμβουλίων…».</li>
        <li>Τις διατάξεις του Ν. 3699/2008 (ΦΕΚ 199/ τ.Α΄/2-10-2008), με θέμα: «Ειδική Αγωγή και Εκπαίδευση ατόμων με αναπηρία ή με ειδικές εκπαιδευτικές ανάγκες». </li>
        <li>Τις διατάξεις του Ν.4186/2013 με θέμα «Αναδιάρθρωση της Δευτεροβάθμιας Εκπαίδευσης και λοιπές διατάξεις».</li>
        <li>Τις διατάξεις του Ν.4354/2015 (ΦΕΚ 176/τ.Α΄/16-12-2015) με θέμα "Διαχείριση των μη εξυπηρετούμενων δανείων, μισθολογικές ρυθμίσεις και άλλες επείγουσες διατάξεις εφαρμογής της συμφωνίας δημοσιονομικών στόχων και διαρθρωτικών μεταρρυθμίσεων"</li>
        <li>Την με αριθμ. 2/31029/ΔΕΠ/06-05-2016 (ΑΔΑ: ΩΛ9ΣΗ-0ΝΜ) εγκύκλιο  του Γ.Λ.Κ. με θέμα «Παροχή οδηγιών για την εφαρμογή των διατάξεων του Κεφαλαίου Β’ του ν.4354/2015(176/Α΄).</li>
        <li>Το με αριθμ. 2/14537/ΔΕΠ/13-05-2016 έγγραφο του ΓΛΚ με θέμα « Παροχή οδηγιών αναφορικά με την ορθή μισθολογική κατάταξη και εξέλιξη του προσωπικού με σχέση εργασίας ιδιωτικού δικαίου ορισμένου χρόνου , που απασχολείται στο Δημόσιο , Ν.Π.Δ.Δ, Ο.Τ.Α, καθώς και των αναπληρωτών εκπαιδευτικών και Ν.Π.Ι.Δ και ΔΕΚΟ της περ.12 της υποπαραγράφου Γ1 της παραγράφου Γ του άρθρου πρώτου του Ν.4093/2012 ». </li>
        <li>Το με αρ. πρωτ. 169228/Ε2/12-10-2016 (ΑΔΑ: 67ΝΓ4653ΠΣ-9ΗΤ)  έγγραφο του ΥΠΠΕΘ, με θέμα «Διευκρινίσεις σχετικά με την εφαρμογή του Ν.4354/2015 στους αναπληρωτές εκπαιδευτικούς».</li>
        <li>Το με αρ. πρωτ. 200022/Δ3/23-11-2016 έγγραφο του ΥΠΠΕΘ με θέμα «Απόδοση συνάφειας μεταπτυχιακών τίτλων σπουδών».</li>
        <li>To με αριθμ. πρωτ. 2/89805/ΔΕΠ/19-01-2017 έγγραφου του ΓΛΚ με θέμα « Παρέχονται πληροφορίες».</li>
        <li>Το με αριθμ. πρωτ.953/14-03-2017 έγγραφο του ΥΠΠΕΘ με θέμα « Διευκρινίσεις σχετικά με τις αρμοδιότητες και καθήκοντα των Περιφερειακών Διευθυντών Εκπαίδευσης και Διευθυντών Α/θμιας και Β/θμιας Εκπαίδευσης, κατά την πρόσληψη των Αναπληρωτών ΕΕΠ και ΕΒΠ».</li>
        <li>Τηγ με αριθμ. πρωτ. 149784/Ε2/11-09-2017 έγγραφο του Υπουργείου Παιδείας Έρευνας και Θρησκευμάτων με θέμα« Απλοποίηση διαδικασιών εφαρμογής του Ν.4354/2015 στους αναπληρωτές εκπαιδευτικούς του σχολικού έτους 2017-2018 ».</li>
        <li>Το με αριθμ. πρωτ. 155255/Ε3/19-09-2018 έγγραφο του Υπουργείου Παιδείας Έρευνας και Θρησκευμάτων με θέμα« Ενημέρωση σχετικά με την απόδοση ΜΚ στους αναπληρωτές εκπ/κούς του σχολικού έτους 2019-2020».</li>
        <li>Το με αρ. πρωτ. Φ.2.5/3279/13-3-2019 έγγραφο της ΠΔΕ Κρήτης με θέμα: «Διαβίβαση αιτήσεων προϋπηρεσίας και μεταπτυχιακών τίτλων σπουδών
            αναπληρωτών ΕΕΠ ». </li>
        <li>Tην  αριθμ.  9/13-3-2019 Πράξη του ΠΥΣΕΕΠ Κρήτης.</li>
    </ol>

    <h4 style="text-align:center">Αποφασίζουμε</h4>
    <p>
        
<!--Την απόδοση προσωρινών Μισθολογικών κλιμακίων  στους παρακάτω αναπληρωτές του  Ειδικού Εκπαιδευτικού Προσωπικού(ΕΕΠ) και Ειδικού Βοηθητικού Προσωπικού( ΕΒΠ) από την ημερομηνία υποβολής των αιτήσεων τους  (αρ. 26, Ν.4354/2015),  και οι οποίοι υπηρετούν στα ΚΕΣΥ , και στις ΣΜΕΑΕ και στα ΣΔΕΥ  αρμοδιότητας της Περιφερειακής Δ/νσης  Α/θμιας και Β/θμιας Εκπ/σης Κρήτης ως εξής:-->
 Την αναγνώριση της προχπηρεσίας και μεταπτυχιακών  τίτλων σπουδών για την μισθολογική εξέλιξη και μισθολογική κατάταξη των παρακάτω 
αναπληρωτών  Ειδικού Εκπαιδευτικού Προσωπικού(ΕΕΠ) και Ειδικού Βοηθητικού Προσωπικού( ΕΒΠ),  οι οποίοι υπηρετούν στα Σχολεία Γενικής Παιδείας
,στα ΚΕΣΥ , στις ΣΜΕΑΕ   και στα  ΕΠΑΛ  αρμοδιότητας της Περιφερειακής Δ/νσης  Α/θμιας και Β/θμιας Εκπ/σης Κρήτης ως εξής: 

    </p>
    
	<table style="width:100%;border-collapse: collapse;">
		<tr>
                        <td <?= $inline_th_css?>><?= Yii::t('substituteteacher', 'Α/Α   ') ?></td>
			<td <?= $inline_th_css?>><?= Yii::t('substituteteacher', 'Teacher') ?></td>
			<td <?= $inline_th_css?>><?= Yii::t('app', 'Father\'s name') ?></td>
			<td <?= $inline_th_css?>><?= Yii::t('app', 'Mother\'s name') ?></td>
			<td <?= $inline_th_css?>><?= Yii::t('app', 'Specialisation') ?></td>
                        <td <?= $inline_th_css?>><?= Yii::t('substituteteacher', 'Operation') ?></td>
                        <td <?= $inline_th_css?>>ΠΡΟΫΠΗΡΕΣΙΑ</td>
			<td <?= $inline_th_css?>>ΜΤΣ</td>
                        <td <?= $inline_th_css?>>ΜΚ</td>
                        <td <?= $inline_th_css?>><?= Yii::t('substituteteacher', 'Ημ/νία Χορήγησης / Κατάταξης') ?></td>
                        <td <?= $inline_th_css?>><?= Yii::t('substituteteacher', 'Placement') ?></td>
		</tr>
                
		<?php   foreach ($mkteacherarr as $mkteacher): $c++;
                        ?>
            		<tr>
                                <td <?= $inline_td_css_right?>><?= $c; ?></td>
            			<td <?= $inline_td_css_left?>><?= $mkteacher['fullname']; ?></td>
            			<td <?= $inline_td_css_left?>><?= $mkteacher['fathername']; ?></td>
            			<td <?= $inline_td_css_left?>><?= $mkteacher['mothername']; ?></td>
                                <td <?= $inline_td_css_left?>><?= $mkteacher['specialty'][0]; ?></td>
                                <td <?= $inline_td_css_left?>><?= $mkteacher['operation']; ?></td>
                                <td <?= $inline_td_css_left?>><?= $mkteacher['mk_expstr']; ?></td>
                                <td <?= $inline_td_css_left?>><?= $mkteacher['mk_titleappdate']; ?></td>
            			<td <?= $inline_td_css_right?>><?= $mkteacher['mk']; ?></td>
                                <td <?= $inline_td_css_right?>><?= $mkteacher['mk_changedate'] ?></td>
                                <td <?= $inline_td_css_left?>><?= $mkteacher['sector']; ?></td>
                        </tr>
                                  
		<?php endforeach;?>
	</table>
	<br /><br />
	<table style="width:100%;border: 0;">
		<tr>
			<td style="text-align:left;width:50%;border: 0;">&nbsp;</td>
			<td style="text-align:right;width:50%;border: 0;">
                            <p><strong><?= Yii::$app->params['director_sign'] ?></strong><br /></p><br/><br/>
                            <p><strong><?= Yii::$app->params['director_name']; ?></strong></p>
			</td>			
                </tr>
                <tr>
                    <td>ΚΟΙΝ:
                        <ol><li>Δ/νσεις  Π.Ε Κρήτης</li>
                            <li>Δ/νσεις  Δ.Ε Κρήτης</li>
                            <li>ΚΕΔΔΥ/ΚΕΣΥ Κρήτης</li>
                            <li>Αναφερόμενους στην Απόφαση (Δια των Δ/νσεων και ΚΕΔΔΥ/ΚΕΣΥ)</li>
                            <li>Εσωτερική διανομή <br/>&nbsp;&nbsp;&nbsp;1. Φ.2.5<br/>&nbsp;&nbsp;&nbsp;2. Φ. πράξης ΕΣΠΑ</li>
                        </ol>
                 <ol>
	</table>
</div>


