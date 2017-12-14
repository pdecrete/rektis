<?php

use yii\helpers\Console;
use yii\db\Migration;

class m160303_075643_employees extends Migration
{

    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'employee';

        Yii::$app->db->createCommand("
create table if not exists `{$table_name}` (
    `id` integer not null auto_increment,
    `status` integer comment 'εργασιακη κατασταση',
    index `status_fk_index` (`status`),
    constraint `fk_status` 
        foreign key `status_fk_index` (`status`)
        references `admapp_employee_status` (`id`)
        on update cascade 
        on delete set null,

    `name` varchar(100) not null,
    `surname` varchar(100) not null,
    `fathersname` varchar(100) not null,
    `mothersname` varchar(100) not null,

    `tax_identification_number` varchar(9) not null comment 'ΑΦΜ',

    `email` varchar(100) not null,
    `telephone` varchar(40) not null,
    `address` varchar(200) not null,
    `identity_number` varchar(40) not null comment 'ταυτοτητα',
    `social_security_number` varchar(40) not null comment 'ΑΜΚΑ',

    `specialisation` integer comment 'Ειδικοτητα',
    index specialisation_fk_index (`specialisation`),
    constraint `fk_specialisation` 
        foreign key `specialisation_fk_index` (`specialisation`)
        references `admapp_specialisation` (`id`)
        on update cascade 
        on delete set null,
    `identification_number` varchar(10) not null comment 'αριθμος μητρωου',
    `appointment_fek` varchar(10) not null comment 'ΦΕΚ διορισμου',
    `appointment_date` date not null comment 'ημερομηνια διορισμου',

    `service_organic` integer comment 'οργανικη θεση',
    index service_organic_fk_index (`service_organic`),
    constraint `fk_service_organic` 
        foreign key `service_organic_fk_index` (`service_organic`)
        references `admapp_service` (`id`)
        on update cascade 
        on delete set null,
    `service_serve` integer comment 'θεση οπου υπηρετει',
    index service_serve_fk_index (`service_serve`),
    constraint `fk_service_serve` 
        foreign key `service_serve_fk_index` (`service_serve`)
        references `admapp_service` (`id`)
        on update cascade 
        on delete set null,

    `position` integer comment 'θεση (ευθύνης κλπ)',
    index position_fk_index (`position`),
    constraint `fk_position` 
        foreign key `position_fk_index` (`position`)
        references `admapp_position` (`id`)
        on update cascade 
        on delete set null,
    `rank` varchar(4) not null comment 'βαθμος',
    `rank_date` date not null,
    `pay_scale` tinyint unsigned not null comment 'μισθολογικο κλιμακιο',
    `pay_scale_date` date not null,
    `service_adoption` varchar(10) not null comment 'αναληψη υπηρεσιας',
    `service_adoption_date` date not null,

    `master_degree` tinyint unsigned not null default 0 comment 'πληθος μεταπτυχιακων τιτλων',
    `doctorate_degree` tinyint unsigned not null default 0 comment 'πληθος διδακτορικων τιτλων',

    `work_experience` integer unsigned not null comment 'προυπηρεσια σε ημερες',
    `comments` longtext not null,

    `create_ts` timestamp not null,
    `update_ts` timestamp not null,

    primary key (`id`),
    unique index admapp_employee_identification_number (`identification_number`),
    unique index admapp_employee_identity_number (`identity_number`)
) engine=InnoDB charset=utf8;
		")
                ->execute();

        $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
        if ($table_schema === null) {
            Console::stdout("Table '{$table_name}' does not appear to exist; considering create unsuccessful.\n", Console::FG_RED);
            return false;
        } else {
            return true;
        }
    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'employee';

        $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
        if ($table_schema === null) {
            Console::stdout("Table '{$table_name}' does not appear to exist; considering drop successful.\n", Console::FG_YELLOW);
        } else {
            Console::stdout("Dropping table '{$table_name}'.\n");
            $this->dropTable($table_name);
        }
    }

}
