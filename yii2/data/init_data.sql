
delete from `admapp_user`;
insert into `admapp_user` (
    `id`,
    `username`,
    `auth_key`,
    `password_hash`,
    `password_reset_token`,
    `email`,
    `name`,
    `surname`,
    `status`,
    `last_login`,
    `create_ts`,
    `update_ts`
) VALUES (
    1,
    'admin',
    '',
    '$2y$13$TrWrgr18LihofvDhzlI3I.XqKe2KfyWiRBRarzi1fJuURqh9oQGHq',
    '',
    'spapad@gmail.com',
    'Stavros',
    'Papadakis',
    10, /* User::STATUS_ACTIVE */
    null,
    current_timestamp,
    current_timestamp
);
alter table `admapp_user` AUTO_INCREMENT=10;

delete from `admapp_position`;
insert into `admapp_position` (`id`, `name`, `comments`) values (1, 'Εκπαιδευτικός', '');
insert into `admapp_position` (`id`, `name`, `comments`) values (2, 'Υποδιευθυντής', '');
insert into `admapp_position` (`id`, `name`, `comments`) values (3, 'Δ/ντής', '');
insert into `admapp_position` (`id`, `name`, `comments`) values (4, 'Προϊστάμενος', '');
insert into `admapp_position` (`id`, `name`, `comments`) values (5, 'Τμ.Ένταξης', '');
insert into `admapp_position` (`id`, `name`, `comments`) values (6, 'Διοικητικός', '');
insert into `admapp_position` (`id`, `name`, `comments`) values (7, 'Ιδιωτικός', '');
alter table `admapp_position` auto_increment=8;

delete from `admapp_employee_status`;
insert into `admapp_employee_status` (`id`, `name`) values (1, 'Εργάζεται');
insert into `admapp_employee_status` (`id`, `name`) values (2, 'Λύση σχέσης - Παραίτηση');
insert into `admapp_employee_status` (`id`, `name`) values (3, 'Άδεια');
insert into `admapp_employee_status` (`id`, `name`) values (4, 'Διαθεσιμότητα');
alter table `admapp_employee_status` auto_increment=5;

delete from `admapp_specialisation`;
alter table `admapp_specialisation` auto_increment=1;
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ [μ]', 'Μετακλητός - ΔΕ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ [ι]', 'Ιδιώτης μέλος επιτροπών - ΔΕ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0100', 'Διοικητικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0101', 'Ηλεκτροτεχνίτης');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0102', 'Μηχανοτεχνίτης');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0104', 'Ηλεκτρονικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0105', 'Οικοδόμος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0106', 'Εμπειρικός Μηχαν/γός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0107', 'Εμπειρικός Ηλεκτρολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0108', 'Ηλεκτροσυγκολητής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0109', 'Βοηθός Χημικού');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0110', 'Τεχνίτης Αυτοκινήτων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0111', 'Τεχνίτης Ψύξεως');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0112', 'Υδραυλικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0113', 'Ξυλουργός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0114', 'Κοπτικής Ραπτικής ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0115', 'Αργυροχρυσοχοιας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0116', 'Τεχν. Αμαξωματων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0117', 'Κομμωτικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0131', 'Ειδικό Βοηθητικό Προσωπικό');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0132', 'Οδηγοί-Συνοδοί');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0200', 'Δακτυλογράφος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 0300', 'Διοικητικός ΔΕ2');
insert into `admapp_specialisation` (`code`, `name`) value ('ΔΕ 1300', 'Συντηρητής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΕΜ1', 'Κινηματογράφου');
insert into `admapp_specialisation` (`code`, `name`) value ('ΕΜ1600', 'Μουσικής Εμπειροτέχνης');
insert into `admapp_specialisation` (`code`, `name`) value ('ΕΜ2', 'Κλασσικού Χορού');
insert into `admapp_specialisation` (`code`, `name`) value ('ΕΜ3', 'Κίνηση-Χορού');
insert into `admapp_specialisation` (`code`, `name`) value ('ΕΜ4', 'Σύγχρονου Χορού');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ', 'Ιδιώτης μέλος επιτροπών - ΠΕ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0100', 'Διοικητικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0101', 'Θεολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0102', 'Διοικητικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0201', 'Φιλόλογος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0301', 'Μαθηματικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0401', 'Φυσικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0402', 'Χημικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0403', 'Φυσιογνώστης');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0404', 'Βιολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0405', 'Γεωλόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0501', 'Γαλλ. Γλώσσας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0601', 'Αγγλ. Γλώσσας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0701', 'Γερμ. Γλώσσας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0801', 'Καλλιτεχν. Μαθημάτων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 0901', 'Οικονομολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1001', 'Κοινωνιολόγος με ΣΕΛΕΤΕ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1002', 'Κοινωνιολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1101', 'Φυσικής Αγωγής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1201', 'Πολιτικός Μηχανικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1202', 'Αρχιτέκτονας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1203', 'Τοπογράφος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1204', 'Μηχανολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1205', 'Ηλεκτρολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1206', 'Ηλ/κος Μηχανικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1208', 'Χημικός Μηχανικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1210', 'Φυσικός Ραδ/γος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1211', 'Μηχανικός Παραγωγής και Διοίκησης');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1301', 'Νομικών-Πολιτικών Επιστημών');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1401', 'Ιατρός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1402', 'Οδοντογιατρός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1403', 'Φαρμακοποιός 5ετους');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1404', 'Γεωπόνος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1405', 'Δασολογίας-Φυσ. Περιβάλλοντος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1406', 'Νοσηλευτικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1407', 'Φαρμακοποιός 4ετους');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1501', 'Οικ. Οικονομίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1601', 'Μουσικής (ΠΕ)');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1602', 'Μουσικής (Ωδείο/TEI)');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1701', 'Τεχν. Πολ. Μηχαν. ΑΣΕΤΕΜ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1702', 'Τεχν. Μηχανολόγος ΑΣΕΤΕΜ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1703', 'Τεχν. Ηλεκτρ/γος ΑΣΕΤΕΜ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1704', 'Τεχν. Ηλεκτρ/κός ΑΣΕΤΕΜ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1705', 'Τεχν. Πολ/κός Μηχ/κός ΤΕΙ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1706', 'Τεχν. Μηχ/λόγος ΤΕΙ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1707', 'Τεχν. Μηχ/ργός ΤΕΙ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1708', 'Τεχν. Ηλεκτρ/κός ΤΕΙ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1709', 'Τεχνολόγοι Ιατρικών Οργάνων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1710', 'Τεχνολόγοι Ενεργειακής Τεχνικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1711', 'Τοπογράφοι ΤΕΙ ΚΑΤΕΕ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1801', 'Γραφικών Τεχνών');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1802', 'Διοικ. Επιχειρήσεων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1803', 'Λογιστικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1804', 'Αισθητικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1807', 'Ιατρ. Εργαστηρίων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1808', 'Οδοντοτεχνικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1809', 'Κοινωνικής Εργασίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1810', 'Νοσηλευτικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1811', 'Μαιευτική');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1812', 'Φυτικής Παραγωγής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1813', 'Ζωικής Παραγωγής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1814', 'Ιχθυοκομίας-Αλιείας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1815', 'Γεωργικών Μηχ/των');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1816', 'Δασοπονίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1817', 'Διοίκ. Γεωργ. Εκμ/σεων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1818', 'Οχημάτων ΤΕΙ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1819', 'Στατιστικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1820', 'Κλωστοϋφαντουργίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1821', 'Ραδ/γίας & Ακτιν/γίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1822', 'Μεταλλειολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1823', 'Ναυτικών Μαθημάτων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1824', 'Εργασιοθεραπείας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1825', 'Φυσιοθεραπείας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1826', 'Γραφιστικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1827', 'Διακοσμητικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1828', 'Συν/σης Αρχ. & Εργ. Τέχνης');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1829', 'Φωτογραφίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1830', 'Εργοδ. Θερμοκηπιακών Καλ/γιών');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1831', 'Μηχανικός Εμπορικού Ναυτικού');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1832', 'Μηχανοσυνθέτης Αεροσκαφών');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1833', 'Βρεφονηπιοκόμος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1834', 'Αργυροχρυσοχοίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1835', 'Τουριστικών Επιχειρήσεων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1836', 'Τεχνολόγος Τροφίμων-Διατροφής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1837', 'Δημόσιας Υγιεινής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1838', 'Κεραμικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1839', 'Επισκεπτριών Υγείας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1840', 'Εμπορίας και Διαφήμισης (Marketing)');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1841', 'Δραματικής Τέχνης');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1901', 'Πληροφορικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1902', 'Εφαρμοσμένης Πληροφορικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 1903', 'Πληροφορικής (ΠΕ5)');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 2001', 'Πληροφορικής ΤΕΙ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 2002', 'Πληροφορικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 2101', 'Θεραπευτής Λόγου');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 2126', 'Λογοθεραπευτής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 2300', 'Ψυχολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 2400', 'Παιδοψυχίατρος Ειδικό Εκπαιδευτικό Προσωπικό');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 2500', 'Σχολική νοσηλεύτρια');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 2600', 'Λογοθεραπευτής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 2800', 'Φυσικοθεραπευτών');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 2900', 'Εργοθεραπευτών');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 3000', 'Ειδικό  Προσωπικό');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 3001', 'Κοινωνικοί Λειτουργοί');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 3200', 'Θεατρικών Σπουδών');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 3201', 'Θεατρικών Σπουδών');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 3300', 'Μεθοδολογίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 3400', 'Ιταλ. Γλώσσας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 4', 'Μετακλητός-Πτυχίο ΑΕΙ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 4000', 'Ισπ. Γλώσσας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 5', 'Μετακλητός-Πτυχίο ΑΕΙ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 6', 'Μετακλητός-Πτυχίο ΑΕΙ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 6000', 'Νηπιαγωγός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 6100', 'Νηπιαγωγός Ειδικής Αγωγής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 7000', 'Δάσκαλος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 7100', 'Δάσκαλος Ειδικής Αγωγής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΠΕ 73', 'Εκπ/κοί Μεειονοτικού Προγ/τος Μειονοτικών Σχολείων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0001', 'Διοικητικός ΤΕ1');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0010', 'Ειδικό Προσωπικό');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0011', 'Πληροφορικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0100', 'Διοικητικός ΤΕ3');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0101', 'Εργοδ. Σχεδιαστής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0102', 'Εργοδ. Μηχανολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0103', 'Εργοδ. Μηχ/κος Αυτοκινήτων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0104', 'Εργοδ. Ψυκτικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0105', 'Εργοδ. Δομικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0106', 'Εργοδ. Ηλεκτρολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0107', 'Εργοδ. Ηλεκτρονικός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0108', 'Εργοδ. Χημικός Εργαστηρίων');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0109', 'Εργοδ. Μηχ/κος Εμπ. Ναυτικού');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0110', 'Εργοδ. Υπάλληλος Γραφείου');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0111', 'Εργοδ. Υπάλληλος Λογιστηρίου');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0112', 'Εργοδ. Διακοσμητικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0113', 'Εργοδ. Προγραμματιστής Η/Υ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0116', 'Μουσικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0117', 'Συντ. Εργ. Τέχνης & Αρχ.');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0119', 'Κομμωτικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0120', 'Αισθητικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0122', 'Εργοδ. Κοπτικής Ραπτικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0123', 'Εργοδ. Μεταλλειολόγος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0124', 'Εργοδ. Ωρολογοποιίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0125', 'Εργοδ. Αργυροχρυσοχοίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0126', 'Εργοδ. Οδοντοτεχνικής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0127', 'Εργοδ. Κλωστοϋφαντουργίας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0128', 'Εργοδ. Αεροσκαφών');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0129', 'Εργοδ. Βοηθ. Ιατρ. & Βιολ. Εργ.');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0130', 'Βοηθός Βρεφοκόμος');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0131', 'Χειριστής Ιατρικών Συσκευών');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0133', 'Φυτικής Παραγωγής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0134', 'Ζωικής Παραγωγής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0201', 'Επιμελητής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0501', 'Φυσικοθεραπευτής');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 0701', 'Κοινωνικός Λειτουργός');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 1601', 'Μουσικοί από Ωδεία');
insert into `admapp_specialisation` (`code`, `name`) value ('ΤΕ 4', 'Μετακλητός - Πτυχίο ΤΕΙ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΥΕ [μ]', 'Μετακλητός - ΥΕ');
insert into `admapp_specialisation` (`code`, `name`) value ('ΥΕ 0110', 'Επιστάτης');
insert into `admapp_specialisation` (`code`, `name`) value ('ΥΕ 0120', 'Κλητήρας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΥΕ 0130', 'Φύλακας-Νυκτοφύλακας');
insert into `admapp_specialisation` (`code`, `name`) value ('ΥΕ 0140', 'Καθαρίστρια');

