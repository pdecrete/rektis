<?php

use yii\db\Migration;
use yii\helpers\Console;

class m181018_180011_eduinventory_teachers extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $table_teacher = $this->db->tablePrefix . 'teacher';
        $table_schoolunit = $this->db->tablePrefix . 'schoolunit';
        $table_specialisation = $this->db->tablePrefix . 'specialisation';
        
        /* CREATE TABLE admapp_disposal_teacher */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $table_teacher .
                          " (`teacher_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `teacher_surname` VARCHAR(100) NOT NULL COMMENT 'Επίθετο',
                             `teacher_name` VARCHAR(100) NOT NULL COMMENT 'Όνομα',
                             `teacher_fathername` VARCHAR(100) COMMENT 'Πατρώνυμο',
                             `teacher_mothername` VARCHAR(100) COMMENT 'Μητρώνυμο',
                             `teacher_gender` BOOLEAN COMMENT 'Φύλο',
                             `teacher_registrynumber` VARCHAR(50) COMMENT 'Αριθμός Μητρώου',
                             `teacher_afm` VARCHAR(50) COMMENT 'ΑΦΜ',
                             `specialisation_id` INTEGER NOT NULL,
                             `school_id` INTEGER NOT NULL,
                              PRIMARY KEY (`teacher_id`),
                              FOREIGN KEY (`specialisation_id`) REFERENCES " . $table_specialisation . " (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`school_id`) REFERENCES " . $table_schoolunit . " (`school_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                            ) " . $tableOptions;
        Console::stdout("\n*** Creating table " . $table_teacher . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        Console::stdout("\n*** Updating/inserting codes of specializations to be consistent with those used by MySchool *** \n");
        //Yii::$app->db->createCommand("UPDATE " . $table_specialisation . " SET `code` = REPLACE(`code`, ' ', '')"); /* Remove redundant blanks e.g. ΠΕ 0100 to ΠΕ0100 */
        
        $this->_insert('ΔΕ02.01', 'Ηλεκτρολόγοι - Ηλεκτρονικοί');
        $this->_insert('ΔΕ02.02', 'Μηχανολόγοι');
        $this->_insert('ΠΕ01.50', 'Θεολόγοι Ειδικής Αγωγής');
        $this->_insert('ΠΕ02.50', 'Φιλόλογοι Ειδικής Αγωγής');        
        $this->_insert('ΠΕ03.50', 'Μαθηματικοί Ειδικής Αγωγής');        
        $this->_insert('ΠΕ04.02.50', 'Χημικός Ειδικής Αγωγής');        
        $this->_insert('ΠΕ04.05.50', 'Γεωλόγος Ειδικής Αγωγής');        
        $this->_insert('ΠΕ06.50', 'Αγγλικής Φιλολογίας Ειδικής Αγωγής');        
        $this->_insert('ΠΕ08.50', 'Καλλιτεχνικών Ειδικής Αγωγής');
        $this->_insert('ΠΕ11.01', 'Ειδικοί Φυσικής Αγωγής');
        $this->_insert('ΠΕ60.50', 'Νηπιαγωγοί Ειδικής Αγωγής');
        $this->_insert('ΠΕ70.50', 'Δάσκαλοι Ειδικής Αγωγής');
        $this->_insert('ΠΕ78', 'Κοινωνικών Επιστημών');
        $this->_insert('ΠΕ78.50', 'Κοινωνικών Επιστημών Ειδικής Αγωγής');
        $this->_insert('ΠΕ79.01', 'Μουσικής Επιστήμης');
        $this->_insert('ΠΕ79,01.50', 'Μουσικής Επιστήμης Ειδικής Αγωγής');
        $this->_insert('ΠΕ80', 'Οικονομίας');
        $this->_insert('ΠΕ80.50', 'Οικονομίας Ειδικής Αγωγής');
        $this->_insert('ΠΕ81', 'Πολ. Μηχανικών - Αρχιτεκτόνων');
        $this->_insert('ΠΕ82', 'Μηχανολόγων');
        $this->_insert('ΠΕ83', 'Ηλεκτρολόγων');
        $this->_insert('ΠΕ84', 'Ηλεκτρονικών');
        $this->_insert('ΠΕ85', 'Χημικών Μηχανικών');
        $this->_insert('ΠΕ86', 'Πληροφορικής');
        $this->_insert('ΠΕ86.50', 'Πληροφορικής Ειδικής Αγωγής');
        $this->_insert('ΠΕ87.01', 'Ιατρικής');
        $this->_insert('ΠΕ87.02', 'Νοσηλευτικής');
        $this->_insert('ΠΕ87.03', 'Αισθητικής');
        $this->_insert('ΠΕ87.04', 'Ιατρικών Εργαστηρίων');
        $this->_insert('ΠΕ87.05', 'Οδοντοτεχνικής');
        $this->_insert('ΠΕ87.06', 'Κοινωνικής Εργασίας');
        $this->_insert('ΠΕ87.08', 'Φυσιοθεραπείας');
        $this->_insert('ΠΕ87.09', 'Βρεφονηπιοκόμων');
        $this->_insert('ΠΕ88.01', 'Γεωπόνοι');
        $this->_insert('ΠΕ88.01.50', 'Γεωπόνοι Ειδικής Αγωγής');
        $this->_insert('ΠΕ88.02', 'Φυτικής Παραγωγής');
        $this->_insert('ΠΕ88.02.50', 'Φυτικής Παραγωγής Ειδικής Αγωγής');
        $this->_insert('ΠΕ88.03', 'Ζωικής Παραγωγήςς');
        $this->_insert('ΠΕ88.03.50', 'Ζωικής Παραγωγής Ειδικής Αγωγής');
        $this->_insert('ΠΕ88.04', 'Διατροφής');
        $this->_insert('ΠΕ88.04.50', 'Διατροφής Ειδικής Αγωγής');
        $this->_insert('ΠΕ88.05', 'Φυσικού Περιβάλλοντος');
        $this->_insert('ΠΕ88.05.50', 'Φυσικού Περιβάλλοντος Ειδικής Αγωγής');
        $this->_insert('ΠΕ89.01', 'Καλλιτεχνικών Σπουδών');
        $this->_insert('ΠΕ89.01.50', 'Καλλιτεχνικών Σπουδών Ειδικής Αγωγής');
        $this->_insert('ΠΕ89.02', 'Σχεδιασμού και Παραγωγής Προϊόντων');
        $this->_insert('ΠΕ90', 'Ναυτικών Μαθημάτων');
        $this->_insert('ΠΕ91.01', 'Θεατρικών Σπουδών');
        $this->_insert('ΠΕ91.01.50', 'Θεατρικών Σπουδών Ειδικής Αγωγής');
        $this->_insert('ΠΕ91.02', 'Δραματικής Τέχνης');
        $this->_insert('ΤΕ01.04', 'Ψυκτικοί');
        $this->_insert('ΤΕ01.07', 'Ηλεκτρονικοί');
        $this->_insert('ΤΕ01.13', 'Προγραμματιστές Η/Υ');
        $this->_insert('ΤΕ01.19', 'Κομμωτικής');
        $this->_insert('ΤΕ01.20', 'Αισθητικής');
        $this->_insert('ΤΕ01.26', 'Οδοντοτεχνικής');
        $this->_insert('ΤΕ01.29', 'Βοηθ. Ιατρ. & Βιολογ. Εργαστηρίων');
        $this->_insert('ΤΕ01.30', 'Βοηθ. Βρεφοκόμοι - Παιδοκόμοι');
        $this->_insert('ΤΕ02.01', 'Σχεδιαστές Δομικοί');
        $this->_insert('ΤΕ02.02', 'Μηχανολόγοι');
        $this->_insert('ΤΕ02.04', 'Οικονομίας - Διοίκησης');
        $this->_insert('ΤΕ16', 'Μουσικής Μη Ανώτατων Ιδρυμάτων');
        $this->_insert('ΤΕ16.00.50', 'Μουσικής Μη Ανώτατων Ιδρυμάτων Ειδικής Αγωγής');
        
        /* Do not insert/update these for compatibilty reasons with the front application that receives applications from special education personner (EBP)*/
        /*
         $this->_insert('ΔΕ1ΕΒΠ', 'Βοηθητικό Προσωπικό Ειδικής Αγωγής');
         $this->_update('ΠΕ 2101', ', 'Θεραπευτής Λόγου');
         $this->_update('ΠΕ 2126', '', 'Λογοθεραπευτής');
         $this->_update('ΠΕ 2300', '', 'Ψυχολόγος');
         $this->_update('ΠΕ 2400', '', 'Παιδοψυχίατρος Ειδικό Εκπαιδευτικό Προσωπικό');
         $this->_update('ΠΕ 2500', '', 'Σχολική νοσηλεύτρια');
         $this->_update('ΠΕ 2600', '', 'Λογοθεραπευτής');
         $this->_update('ΠΕ 2800', '', 'Φυσικοθεραπευτών');
         $this->_update('ΠΕ 2900', '', 'Εργοθεραπευτών');
         $this->_update('ΠΕ 3000', '', 'Ειδικό  Προσωπικό');
         $this->_update('ΠΕ 3001', '', 'Κοινωνικοί Λειτουργοί');*/
        
        
        $this->_update('ΔΕ 0101', 'ΔΕ01.01', 'Ηλεκτροτεχνίτης');; 
        $this->_update('ΔΕ 0102', 'ΔΕ01.02', 'Μηχανοτεχνίτης');
        $this->_update('ΔΕ 0104', 'ΔΕ01.04', 'Ηλεκτρονικός');
        $this->_update('ΔΕ 0105', 'ΔΕ01.05', 'Οικοδόμος');
        $this->_update('ΔΕ 0106', 'ΔΕ01.06', 'Εμπειρ. Μηχανολόγος');
        $this->_update('ΔΕ 0107', 'ΔΕ01.07', 'Εμπειρ. Ηλεκτρολόγος');
        $this->_update('ΔΕ 0108', 'ΔΕ01.08', 'Ηλεκτροσυγκολλητής');
        $this->_update('ΔΕ 0109', 'ΔΕ01.09', 'Βοηθός Χημικού');
        $this->_update('ΔΕ 0110', 'ΔΕ01.10', 'Τεχνίτης Αυτοκινήτου');
        $this->_update('ΔΕ 0111', 'ΔΕ01.11', 'Τεχνίτης Ψύξεων (Ψυκτικοί)');
        $this->_update('ΔΕ 0112', 'ΔΕ01.12', 'Υδραυλικός');
        $this->_update('ΔΕ 0113', 'ΔΕ01.13', 'Ξυλουργός');
        $this->_update('ΔΕ 0114', 'ΔΕ01.14', 'Κοπτικής - Ραπτικής');
        $this->_update('ΔΕ 0115', 'ΔΕ01.15', 'Αργυροχρυσοχοϊας');
        $this->_update('ΔΕ 0116', 'ΔΕ01.16', 'Τεχν. Αμαξωμάτων');
        $this->_update('ΔΕ 0117', 'ΔΕ01.17', 'Κομμωτικής');
        $this->_update('ΠΕ 0201', 'ΠΕ02', 'Φιλόλογος');
        $this->_update('ΠΕ 0301', 'ΠΕ03', 'Μαθηματικός');
        $this->_update('ΠΕ 0401', 'ΠΕ04.01', 'Φυσικός');
        $this->_update('ΠΕ 0402', 'ΠΕ04.02', 'Χημικός');
        $this->_update('ΠΕ 0403', 'ΠΕ04.03', 'Φυσιογνώστης');
        $this->_update('ΠΕ 0404', 'ΠΕ04.04', 'Βιολόγος');
        $this->_update('ΠΕ 0405', 'ΠΕ04.05', 'Γεωλόγος');
        $this->_update('ΠΕ 0501', 'ΠΕ05', 'Γαλλικής Φιλολογίας');
        $this->_update('ΠΕ 0601', 'ΠΕ06', 'Αγγλικής Φιλολογίας');
        $this->_update('ΠΕ 0701', 'ΠΕ07', 'Γερμανικής Φιλολογίας');
        $this->_update('ΠΕ 0801', 'ΠΕ08', 'Καλλιτεχνικών');
        $this->_update('ΠΕ 0901', 'ΠΕ09', 'Οικονομολόγος');
        $this->_update('ΠΕ 1001', 'ΠΕ10', 'Κοινωνιολόγος');
        //$this->_update('ΠΕ 1002', 'ΠΕ10', 'Κοινωνιολος');
        $this->_update('ΠΕ 1101', 'ΠΕ11', 'Φυσικής Αγωγής');
        $this->_update('ΠΕ 1201', 'ΠΕ12.01', 'Πολιτικός Μηχανικός');
        $this->_update('ΠΕ 1202', 'ΠΕ12.02', 'Αρχιτέκτονας');
        $this->_update('ΠΕ 1203', 'ΠΕ12.03', 'Τοπογράφος');
        $this->_update('ΠΕ 1204', 'ΠΕ12.04', 'Μηχανολόγος');
        $this->_update('ΠΕ 1205', 'ΠΕ12.05', 'Ηλεκτρολόγος');
        $this->_update('ΠΕ 1206', 'ΠΕ12.06', 'Ηλ/κος Μηχανικός');
        $this->_update('ΠΕ 1208', 'ΠΕ12.08', 'Χημικός Μηχανικός');
        $this->_update('ΠΕ 1210', 'ΠΕ12.10', 'Φυσικός Ραδ/γος');
        $this->_update('ΠΕ 1211', 'ΠΕ12.11', 'Μηχανικός Παραγωγής και Διοίκησης');
        $this->_update('ΠΕ 1301', 'ΠΕ13', 'Νομικών-Πολιτικών Επιστημών');
        $this->_update('ΠΕ 1401', 'ΠΕ 14.01', 'Ιατρός');
        $this->_update('ΠΕ 1402', 'ΠΕ 14.02', 'Οδοντίατρος');
        $this->_update('ΠΕ 1403', 'ΠΕ 14.03', 'Φαρμακοποιός');
        $this->_update('ΠΕ 1404', 'ΠΕ 14.04', 'Γεωπόνος');
        $this->_update('ΠΕ 1405', 'ΠΕ 14.05', 'Δασολογίας-Φυσ. Περιβάλλοντος');
        $this->_update('ΠΕ 1406', 'ΠΕ 14.06', 'Νοσηλευτικής');
        $this->_update('ΠΕ 1501', 'ΠΕ15', 'Οικ. Οικονομίας');
        $this->_update('ΠΕ 1601', 'ΠΕ16.01', 'Μουσικής Πτυχ. Μουσικής Επιστήμης');
        $this->_update('ΠΕ 1602', 'ΠΕ16.02', 'Μουσικής Πτυχ. Παραδοσιακής/Λαϊκής Μουσικής');
        $this->_update('ΠΕ 1701', 'ΠΕ17.01', 'Πολ. Μηχαν. ΑΣΕΤΕΜ');
        $this->_update('ΠΕ 1702', 'ΠΕ17.02', 'Μηχανολόγος ΑΣΕΤΕΜ');
        $this->_update('ΠΕ 1703', 'ΠΕ17.03', 'Ηλεκτρ/γος ΑΣΕΤΕΜ');
        $this->_update('ΠΕ 1704', 'ΠΕ17.04', 'Ηλεκτρ/κός ΑΣΕΤΕΜ');
        $this->_update('ΠΕ 1705', 'ΠΕ17.05', 'Πολ/κός Μηχ/κός ΤΕΙ ΚΑΤΕΕ');
        $this->_update('ΠΕ 1706', 'ΠΕ17.06', 'Μηχ/γοι ΤΕΙ - Ναυπ. Εμπ. Ναυτ. ΤΕΙ-ΚΑΤΕΕ - Τεχ/γοι Ενεργ. Τεχν.');
        $this->_update('ΠΕ 1707', 'ΠΕ17.07', 'Μηχ/ργός ΤΕΙ');
        $this->_update('ΠΕ 1708', 'ΠΕ17.08', 'Τεχν. Ηλεκτρ/κός ΤΕΙ');
        $this->_update('ΠΕ 1709', 'ΠΕ17.09', 'Τεχνικός Ιατρικών Οργάνων');
        $this->_update('ΠΕ 1710', 'ΠΕ17.10', 'Τεχνολόγος Ενεργειακής Τεχνικής');
        $this->_update('ΠΕ 1711', 'ΠΕ17.11', 'Τοπογράφος ΤΕΙ ΚΑΤΕΕ');
        $this->_update('ΠΕ 1801', 'ΠΕ18.01', 'Γραφικών Τεχνών');
        $this->_update('ΠΕ 1802', 'ΠΕ18.02', 'Διοικ. Επιχειρήσεων');
        $this->_update('ΠΕ 1803', 'ΠΕ18.03', 'Λογιστικής');
        $this->_update('ΠΕ 1804', 'ΠΕ18.04', 'Αισθητικής');
        $this->_update('ΠΕ 1807', 'ΠΕ18.07', 'Ιατρικών Εργαστηρίων');
        $this->_update('ΠΕ 1808', 'ΠΕ18.08', 'Οδοντοτεχνικής');
        $this->_update('ΠΕ 1809', 'ΠΕ18.09', 'Κοινωνικής Εργασίας');
        $this->_update('ΠΕ 1810', 'ΠΕ18.10', 'Νοσηλευτικής');
        $this->_update('ΠΕ 1811', 'ΠΕ18.11', 'Μαιευτική');
        $this->_update('ΠΕ 1812', 'ΠΕ18.12', 'Φυτικής Παραγωγής');
        $this->_update('ΠΕ 1813', 'ΠΕ18.13', 'Ζωικής Παραγωγής');
        $this->_update('ΠΕ 1814', 'ΠΕ18.14', 'Ιχθυοκομίας - Αλιείας');
        $this->_update('ΠΕ 1815', 'ΠΕ18.15', 'Γεωργικών Μηχ/των και Αρδεύσεων');
        $this->_update('ΠΕ 1816', 'ΠΕ18.16', 'Δασοπονίας');
        $this->_update('ΠΕ 1817', 'ΠΕ18.17', 'Διοίκ. Γεωργ. Εκμ/σεων');
        $this->_update('ΠΕ 1818', 'ΠΕ18.18', 'Οχημάτων ΤΕΙ');
        $this->_update('ΠΕ 1819', 'ΠΕ18.19', 'Στατιστικής');
        $this->_update('ΠΕ 1820', 'ΠΕ18.20', 'Κλωστοϋφαντουργίας');
        $this->_update('ΠΕ 1821', 'ΠΕ18.21', 'Ραδ/γίας & Ακτιν/γίας');
        $this->_update('ΠΕ 1822', 'ΠΕ18.22', 'Μεταλλειολόγος');
        $this->_update('ΠΕ 1823', 'ΠΕ18.23', 'Ναυτικών Μαθημάτων (Πλοίαρχοι)');
        $this->_update('ΠΕ 1824', 'ΠΕ18.24', 'Εργασιοθεραπείας');
        $this->_update('ΠΕ 1825', 'ΠΕ18.25', 'Φυσιοθεραπείας');
        $this->_update('ΠΕ 1826', 'ΠΕ18.26', 'Γραφιστικής');
        $this->_update('ΠΕ 1827', 'ΠΕ18.27', 'Διακοσμητικής');
        $this->_update('ΠΕ 1828', 'ΠΕ18.28', 'Συντηρητής Έργων Τέχνης και Αρχ. Ευρημάτων');
        $this->_update('ΠΕ 1829', 'ΠΕ18.29', 'Φωτογραφίας');
        $this->_update('ΠΕ 1830', 'ΠΕ18.30', 'Θερμοκηπιακών Καλ/γιών και Ανθ/μίας');
        $this->_update('ΠΕ 1831', 'ΠΕ18.31', 'Μηχανικός Εμπορικού Ναυτικού');
        $this->_update('ΠΕ 1832', 'ΠΕ18.32', 'Μηχανοσυνθέτης Αεροσκαφών');
        $this->_update('ΠΕ 1833', 'ΠΕ18.33', 'Βρεφονηπιοκόμος');
        $this->_update('ΠΕ 1834', 'ΠΕ18.34', 'Αργυροχρυσοχοίας');
        $this->_update('ΠΕ 1835', 'ΠΕ18.35', 'Τουριστικών Επιχειρήσεων');
        $this->_update('ΠΕ 1836', 'ΠΕ18.36', 'Τεχνολόγος Τροφίμων-Διατροφής');
        $this->_update('ΠΕ 1837', 'ΠΕ18.37', 'Δημόσιας Υγιεινής');
        $this->_update('ΠΕ 1838', 'ΠΕ18.38', 'Κεραμικής');
        $this->_update('ΠΕ 1839', 'ΠΕ18.39', 'Επισκέπτης Υγείας');
        $this->_update('ΠΕ 1840', 'ΠΕ18.40', 'Εμπορίας και Διαφήμισης (Marketing)');
        $this->_update('ΠΕ 1841', 'ΠΕ18.41', 'Δραματικής Τέχνης');        
        $this->_update('ΠΕ 3200', 'ΠΕ32', 'Θεατρικών Σπουδών');
        $this->_update('ΠΕ 3300', 'ΠΕ33', 'Μεθοδολογίας');
        $this->_update('ΠΕ 3400', 'ΠΕ34', 'Ιταλικής Φιλολογίας');
        $this->_update('ΠΕ 4000', 'ΠΕ40', 'Ισπανικής Φιλογολίας');
        $this->_update('ΠΕ 4000', 'ΠΕ41', 'Θεωρίας και Ιστορίας της Τέχνης');
        $this->_update('ΠΕ 6000', 'ΠΕ60', 'Νηπιαγωγός');
        $this->_update('ΠΕ 6100', 'ΠΕ61', 'Νηπιαγωγός Ειδικής Αγωγής');
        $this->_update('ΠΕ 7000', 'ΠΕ70', 'Δάσκαλος');
        $this->_update('ΠΕ 7100', 'ΠΕ71', 'Δάσκαλος Ειδικής Αγωγής');
        $this->_update('ΠΕ 73', 'ΠΕ73', 'Δάσκαλος Μειονοτικού Προγ/τος');
    }
    
    public function _insert($code, $description)
    {
        $table_specialisation = $this->db->tablePrefix . 'specialisation';        
        $select_command = "SELECT * FROM " . $table_specialisation . " WHERE `code` LIKE '" . $code . "'"; 
        $insert_command = "INSERT INTO " . $table_specialisation . " (`code`, `name`) VALUES ";
        
        if(Yii::$app->db->createCommand($select_command)->query()->count() == 0)
            echo Yii::$app->db->createCommand($insert_command . "('" . $code . "', '" . $description . "')")->rawSql;//->execute();
        Console::stdout("Finished insertions\n");
    }
    
    public function _update($oldcode, $newcode, $description)
    {
        $table_specialisation = $this->db->tablePrefix . 'specialisation';
        $select_newcode_command = "SELECT * FROM " . $table_specialisation . " WHERE `code` LIKE '" . $newcode . "'";
        $select_oldcode_command = "SELECT * FROM " . $table_specialisation . " WHERE `code` LIKE '" . $oldcode . "'";
        $update_command = "UPDATE " . $table_specialisation . " SET ";
        
        if(Yii::$app->db->createCommand($select_oldcode_command)->query()->count() == 1) {
            if(Yii::$app->db->createCommand($select_newcode_command)->query()->count() == 0) /* Avoid unique key constraint violation */
                Yii::$app->db->createCommand($update_command . " `code`='" . $newcode . "', `name`='" . $description . "' WHERE `code` LIKE '" . $oldcode . "'")->execute();
            else
                Yii::$app->db->createCommand($update_command . " `name`='" . $description . "' WHERE `code` LIKE '" . $newcode . "'")->execute();
        }
        else if(Yii::$app->db->createCommand($select_newcode_command)->query()->count() == 1)
            Yii::$app->db->createCommand($update_command . " `name`='" . $description . "' WHERE `code` LIKE '" . $newcode . "'")->execute();
        else
            $this->_insert($newcode, $description);
        
    }
    
    public function safeDown()
    {
        $table_teacher = $this->db->tablePrefix . 'teacher';        
        Console::stdout("Dropping table " . $table_teacher . ".\n");
        $this->dropTable($table_teacher);
    }
}
