\W
INSERT INTO `admapp_transport_funds` (`name`, `date`, `ada`, `service`, `code`, `kae`) VALUES ('Φ.2.2/11', STR_TO_DATE('4/1/2016', '%d/%m/%Y'), '7ΧΓ84653ΠΣ-Β19', (SELECT `id` FROM `admapp_service` WHERE `name`='ΠΔΕ ΚΡΗΤΗΣ'), '90-21/185', '719');
INSERT INTO `admapp_transport_funds` (`name`, `date`, `ada`, `service`, `code`, `kae`) VALUES ('Φ.2.2/12', STR_TO_DATE('4/1/2016', '%d/%m/%Y'), '72ΨΨ4653ΠΣ-Ψ5Ρ', (SELECT `id` FROM `admapp_service` WHERE `name`='ΠΔΕ ΚΡΗΤΗΣ'), '90-21/185', '721');
INSERT INTO `admapp_transport_funds` (`name`, `date`, `ada`, `service`, `code`, `kae`) VALUES ('3116', STR_TO_DATE('4/1/2016', '%d/%m/%Y'), '', (SELECT `id` FROM `admapp_service` WHERE `name`='ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΗΡΑΚΛΕΙΟΥ'), '90-36/182', '719');
INSERT INTO `admapp_transport_funds` (`name`, `date`, `ada`, `service`, `code`, `kae`) VALUES ('3863', STR_TO_DATE('4/1/2016', '%d/%m/%Y'), '', (SELECT `id` FROM `admapp_service` WHERE `name`='ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΗΡΑΚΛΕΙΟΥ'), '90-36/182', '721');
INSERT INTO `admapp_transport_funds` (`name`, `date`, `ada`, `service`, `code`, `kae`) VALUES ('3864', STR_TO_DATE('4/1/2016', '%d/%m/%Y'), '', (SELECT `id` FROM `admapp_service` WHERE `name`='ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΗΡΑΚΛΕΙΟΥ'), '90-36/182', '722');
INSERT INTO `admapp_transport_funds` (`name`, `date`, `ada`, `service`, `code`, `kae`) VALUES ('19', STR_TO_DATE('8/1/2016', '%d/%m/%Y'), '7Σ8Κ4653ΠΣ-ΦΜΙ', (SELECT `id` FROM `admapp_service` WHERE `name`='ΔΙΕΥΘΥΝΣΗ Π.Ε. ΛΑΣΙΘΙΟΥ'), '90-36/181', '719');
INSERT INTO `admapp_transport_funds` (`name`, `date`, `ada`, `service`, `code`, `kae`) VALUES ('20', STR_TO_DATE('8/1/2016', '%d/%m/%Y'), 'ΩΖΙΣ4653ΠΣ-ΦΒ9', (SELECT `id` FROM `admapp_service` WHERE `name`='ΔΙΕΥΘΥΝΣΗ Π.Ε. ΛΑΣΙΘΙΟΥ'), '90-36/181', '721');
/* Done. */
