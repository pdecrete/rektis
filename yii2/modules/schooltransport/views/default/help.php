<?php

use app\modules\schooltransport\Module;
use yii\helpers\Url;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->title = Module::t('modules/schooltransport/app', 'Help');
$this->params['breadcrumbs'][] = $this->title;

$collapse_in = [1 => "", 2 => "", 3 => ""];
if(array_key_exists($helpId, $collapse_in)){
    $collapse_in[$helpId] = 'in';
}
?>

<div class="body-content">
  <div class="panel-group" id="accordion">
  	
    <div class="panel panel-default">
      <a id="schtransportsapp_help"></a>
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Βοήθεια σχετικά με την εφαρμογή των σχολικών μετακινήσεων.</a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse <?php echo $collapse_in[1];?>">
        <div class="panel-body">
        	<h3>1. Προβολή και διαθέσιμες ενέργειες Εγκρίσεων Μετακινήσεων</h3>
        	<p>Για την προβολή των δημιουργημένων εγκρίσεων μετακινήσεων και την πρόσβαση στις αντίστοιχες λειτουργίες τους, από το μενού
        	"Σχολικές Μετακινήσεις" επιλέγουμε "Εγκρίσεις Μετακινήσεων".</p>
        	<p>Οι διαθέσιμες λειτουργίες είναι:</p>
        	<ul>
        		<li>Δημιουργία Μετακίνησης</li>
        		<li>Προβολή Μετακίνησης</li>
        		<li>Διαγραφή Μετακίνησης (η διαγραφή επιτρέπεται μόνο όταν η μετακίνηση βρίσκεται στην αρχική κατάσταση)</li>
        		<li>Λήψη αρχείου έγκρισης μετακίνησης</li>
        		<li>Λήψη ψηφιακά υπογεγραμμένου αρχείου έγκρισης μετακίνησης (μόνο εφόσον έχει μεταφορτωθεί)</li>
        		<li>Προώθηση σε επόμενη κατάσταση (κατά την προώθηση σε επόμενη κατάσταση εμφανίζεται σχετικό εικονίδιο πάνω στο οποίο 
        		όταν βρεθεί το ποντίκι εμφανίζονται πληροφορίες σχετικές με την συγκεκριμένη κατάσταση της μετακίνησης.</li>
        		<li>Μετάβαση σε προηγούμενη κατάσταση (με αυτή τη λειτουργία διαγράφονται όλες οι πληροφορίες που είναι σχετικές 
        		με αυτή την κατάσταση)</li>
        	</ul>
        	<hr />
            <h3>2. Δημιουργία Έγκρισης Μετακίνησης</h3>
            Για τη δημιουργία Έγκρισης Σχολικής Μετακίνησης:
                <ul> 
                    <li>πηγαίνουμε στο μενού "Σχολικές Μετακινήσεις" και επιλέγουμε "Εγκρίσεις Μετακινήσεων" όπου βλέπουμε όλες τις εγκρίσεις 
                    μετακινήσεων που έχουν δημιουργηθεί,</li> 
                    <li>πατάμε το κουμπί "Δημιουργία Μετακίνησης" και στο πάνελ που εμφανίζεται επιλέγουμε τον τύπο της Σχολικής Μετακίνησης
                    που θέλουμε να δημιουργήσουμε,</li>
                    <li>συμπληρώνουμε τη φόρμα με τα στοιχεία της μετακίνησης,</li>
                    <li>πατάμε το κουμπί "Δημιουργία" και η εφαρμογή επιστρέφει στο σημείο όπου εμφανίζονται όλες οι δημιουργημένες 
                    μετακινήσεις.</li> 
                </ul>
            <p>
            Για να εκτυπώσουμε την απόφαση της Έγκρισης πατάμε από την αντίστοιχη μετακίνηση το σύνδεσμο για το "κατέβασμα" του αρχείου.
            </p>
            <hr />
            <h3>3. Παράμετροι</h3>
            <h4>3.1. Σχολικές Μονάδες</h4>
            <p>Στο μενού "Σχολικές Μετακινήσεις" στην επιλογή "Σχολικές Μονάδες" μπορούμε να δούμε όλες τις σχολικές μονάδες που υπάρχουν
            αποθηκευμένες στη βάση δεδομένων της εφαρμογής. Πατώντας το κουμπί "Ενημέρωση Στοιχείων Σχολείων" αντλούνται τα στοιχεία των σχολείων
            από το MySchool και αυτά ενημέρωνονται στη βάση δεδομένων της εφαρμογής.</p>
            <p>  
            Τα ονόματα των σχολικών μονάδων αυτών χρησιμοποιούνται στη φόρμα της δημιουργίας 
            έγκρισης μετακίνησης στην οποία η εισαγωγή του σχολείου γίνεται από ένα πεδίο όπου αρκεί η αναζήτηση του σχολείου σε αυτό 
            (απλά εισάγοντας ένα μέρος του ονόματός του).            
            </p>
            <h4>3.2. Καταστάσεις Εγκρίσεων</h4>
            <p>Στο μενού "Σχολικές Μετακινήσεις" στην επιλογή "Καταστάσεις Εγκρίσεων" φαίνονται οι διαθέσιμες καταστάσεις μέσω των οποίων
            περνάει η διαδικασία Έγκρισης μιας Σχολικής Μετακίνησης από τη δημιουργία μέχρι και την διεκπεραίωσή της.</p>
            <p>Οι καταστάσεις αυτές είναι:</p>
            <ul>
                <li>Ψηφιακά Υπογεγραμμένη<i>(αν έχει δημιουργηθεί ψηφιακά υπογεγραμμένο αρχείο της έγκρισης)</i></li>
                <li>Πρωτοκολλήθηκε/Ξεχρεώθηκε</li>
                <li>Διεκπεραιώθηκε(αν η έγκριση έχει αποσταλεί)</li>
            </ul>
            <hr />
            <h3>3. Βοήθεια</h3>
            <h4>3.1. Βοήθεια εφαρμογής σχολικών μετακινήσεων</h4>
            <p>Στην ενότητα αυτή παρέχονται οδηγίες για τη χρήση της εφαρμογής των Σχολικών Μετακινήσεων</p>
            <h4>3.2. Διαδικασία έγκρισης σχολικής μετακίνησεις</h4>
            <p>Στην ενότητα αυτή παρέχονται οδηγίες για τα στοιχεία που πρέπει να ελεγχθούν στα δικαιολογητικά που έχουν παραληφθεί από
            τη Διεύθυνση Εκπαίδευσης του σχολείου που ζητάει την έγκριση μετακίνησης.</p>
            <h4>3.3. Νομοθεσία σχολικών μετακινήσεων</h4>
            <p>Στην ενότητα αυτή υπάρχουν τα αρχεία με τη νομοθεσία που είναι σχετική με τις σχολικές μετακινήσεις.</p> 
		</div>
      </div>
    </div>
    
    <div class="panel panel-default">
      <a id="schtransports_help"></a>
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Βοήθεια σχετικά με τη δημιουργία έγκρισης σχολικής μετακίνησης.</a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse <?php echo $collapse_in[2];?>">
        <div class="panel-body">
        <p>Για την έγκριση μιας σχολικής μετακίνησης (εκτός αυτών που αφορούν το Σχολείο Ευρωπαϊκής Παιδείας) απαιτούνται τα ακόλουθα δικαιολογητικά
        που διαβιβάζονται στην Περιφερειακή Διεύθυνση Εκπαίδευσης μέσω της οικίας Διεύθυνσης Εκπαίδευσης του σχολείου:</p>
        <ol>
        	<li>Διαβιβαστικό με θετική εισήγηση από τη Διεύθυνση Εκπαίδευσης στην οποία ανήκει το σχολείο.</li>
        	<li>Αίτηση του σχολείου ή των εκπαιδευτικών που μετακινούνται.</li>
        	<li>Πρόσκληση από το φορέα υποδοχής προς τον οποίο γίνεται η μετακίνηση και στην οποία θα πρέπει να αναφέρονται τα ονόματα των
        	μετακινούμενων εκπαιδευτικών και μαθητών/τριών (εφόσον υπάρχουν).</li>
        	<li>Σύμβαση μεταξύ του σχολείου και του φορέα υποδοχής (αρκεί η 1η και τελευταία σελίδα) στην οποία αναφέρεται ο κωδικός του 
        	προγράμματος (αφορά προγράμματα Erasmus). <br />Εναλλακτικά αντί της Σύμβασης αρκεί και η αίτηση από το ΙΚΥ.</li> 
        	<li>Πρόγραμμα συνάντησης από τον φορέα υποδοχής (ελέγχουμε η έναρξη της μετακίνησης να είναι 1 μέρα πριν την έναρξη της συνάντησης και η λήξη 1 μέρα μετά τη λήξη της συνάντησης
        		για την οποία γίνεται η μετακίνηση.).</li>
        	<li>Πρακτικό Απόφασης Συλλόγου Διδασκόντων του σχολείου με το οποίο γίνεται ο καθορισμός της Παιδαγωγικής Ομάδας του προγράμματος.</li>
        	<li>Πρακτικό Απόφασης Συλλόγου Διδασκόντων του σχολείου για τη συγκεκριμένη μετακίνηση.</li>
        	<li>Διαβιβαστικό του σχολείου προς την οικία Διεύθυνση Εκπαίδευσης του σχολείου.</li>
        </ol>        
		</div>
      </div>
    </div>
   
    <div class="panel panel-default">
      <a id="legislation"></a>
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Νομοθεσία σχετική με τις σχολικές μετακινήσεις.</a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse <?php echo $collapse_in[3];?>">
        <div class="panel-body">
        	<ul>
    			<li><a href="<?php echo Url::to(['legislation', 'fileId' => 2]);?>">ΦΕΚ 2769 / Εκδρομές-Μετακινήσεις μαθητών Δημοσίων και Ιδιωτικών σχολείων Δευτεροβάθμιας Εκπαίδευσης εντός και εκτός της χώρας.</a> (<i>02/12/2011</i>)</li>
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 1]);?>">Εκδρομές-μετακινήσεις μαθητών Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσεις στο εξωτερικό.</a> (<i>Έγγραφο ΥΠΕΠΘ: Φ10/4218/Δ2 - 11/01/2017</i>)</li>
    			<li><a href="<?php echo Url::to(['legislation', 'fileId' => 3]);?>">ΦΕΚ 681 / Εκδρομές-Μετακινήσεις μαθητών Δημοσίων και Ιδιωτικών σχολείων Δευτεροβάθμιας Εκπαίδευσης εντός και εκτός της χώρας.</a> (<i>06/03/2017</i>)</li>				
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 6]);?>">Μετακινήσεις μαθητών Δημοτικών Σχολείων στη Βουλή των Ελλήνων - Συμμετοχή στο Εργαστήρι Δημοκρατίας.</a> (<i>Έγγραφο ΥΠΕΠΘ: Φ.12/ΦΜ/48140/Δ1 - 21/03/2017</i>)</li>				
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 4]);?>">ΦΕΚ 109 / Προεδρικό Διάταγμα υπ' αριθμ. 79.</a> <i>(01/08/2017)</i></li>
				<li><a href="<?php echo Url::to(['legislation', 'fileId' => 5]);?>">Μετακινήσεις μαθητών Δημοτικών Σχολείων στη Βουλή των Ελλήνων - Συμμετοχή στο Εργαστήρι Δημοκρατίας.</a> (<i>Έγγραφο ΥΠΕΠΘ: Φ.12/ΦΜ/53243/Δ1 - 02/04/2018</i>)</li>		
			</ul>
        <p><em>Τελευταία Ενημέρωση: 05-05-2018</em></p>
        </div>
      </div>
    </div>

</div>
</div>