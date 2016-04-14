<?php

use yii\helpers\Console;
use yii\db\Migration;
use yii\db\Expression;

class m160414_103842_adeies extends Migration
{

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'leave';
        $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
        if ($table_schema === null) {
            Console::stdout("Table '{$table_name}' does not appear to exist; considering drop successful.\n", Console::FG_YELLOW);
        } else {
            Console::stdout("Dropping table '{$table_name}'.\n");
            $this->dropTable($table_name);
        }

        $types_table_name = $this->db->tablePrefix . 'leave_type';
        $types_table_schema = Yii::$app->db->schema->getTableSchema($types_table_name);
        if ($types_table_schema === null) {
            Console::stdout("Table '{$types_table_name}' does not appear to exist; considering drop successful.\n", Console::FG_YELLOW);
        } else {
            Console::stdout("Dropping table '{$types_table_name}'.\n");
            $this->dropTable($types_table_name);
        }
    }

    public function safeUp()
    {
        $types_table_name = $this->db->tablePrefix . 'leave_type';

        Console::stdout("Creating leave types table.\n", Console::FG_YELLOW);
        Yii::$app->db->createCommand("
create table if not exists `{$types_table_name}` (
    `id` integer not null auto_increment,
    `name` varchar(100) not null,
    `description` longtext not null,
    `create_ts` timestamp not null,
    `update_ts` timestamp not null,
    primary key (`id`),
    unique index leave_name (`name`)
) engine=InnoDB charset=utf8;
		")
                ->execute();

        $types_table_schema = Yii::$app->db->schema->getTableSchema($types_table_name);
        if ($types_table_schema === null) {
            Console::stdout("Table '{$types_table_name}' does not appear to exist; considering create unsuccessful.\n", Console::FG_RED);
            return false;
        }

        Console::stdout("Populating core leave types.\n", Console::FG_YELLOW);
        Yii::$app->db->createCommand()->batchInsert($types_table_name, ['id', 'name', 'description', 'create_ts', 'update_ts'], [
            [1, 'Αναρρωτική με ιατρική γνωμάτευση', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [2, 'Αναρρωτική με υπεύθυνση δήλωση', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [3, 'Ανατροφής', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [4, 'Άνευ αποδοχών', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [5, 'Για επιστημονικούς ή επιμορφωτικούς λόγους', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [6, 'Γονική (Σχολικής επίδοσης)', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [7, 'Ειδική', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [8, 'Εκλογική', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [9, 'Εξετάσεων', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [10, 'Κανονική', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [11, 'Κύησης', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
            [12, 'Λοχείας', '', new Expression('CURRENT_TIMESTAMP'), new Expression('CURRENT_TIMESTAMP')],
        ])->execute();
        Console::stdout("Setting auto increment to 15.\n", Console::FG_YELLOW);
        Yii::$app->db->createCommand("alter table `{$types_table_name}` AUTO_INCREMENT=15;")
                ->execute();


        $table_name = $this->db->tablePrefix . 'leave';

        Console::stdout("Creating leave table.\n", Console::FG_YELLOW);
        Yii::$app->db->createCommand("
            create table if not exists `{$table_name}` (
                `id` integer not null auto_increment,
                `employee` integer comment 'Υπάλληλος',
                index `employee_fk_index` (`employee`),
                constraint `fk_employee`
                    foreign key `employee_fk_index` (`employee`)
                    references `admapp_employee` (`id`)
                    on update cascade
                    on delete set null,
                `type` integer comment 'Τύπος άδειας',
                index leave_type_fk_index (`type`),
                constraint `fk_leave_type`
                    foreign key `leave_type_fk_index` (`type`)
                    references `admapp_leave_type` (`id`)
                    on update cascade
                    on delete set null,
                `decision_protocol` integer not null comment 'Πρωτόκολλο απόφασης',
                `decision_protocol_date` date not null comment 'Ημερομηνία απόφασης',
                `application_protocol` integer not null comment 'Πρωτόκολλο αίτησης',
                `application_protocol_date` date not null comment 'Ημερομηνία πρωτοκόλλου αίτησης',
                `application_date` date not null comment 'Ημερομηνία  αίτησης',
                `accompanying_document` varchar(100) comment 'Συνοδευτικά έγγραφα (βεβαίωση, δήλωση για αναρρωτική, κλπ.',
                `duration` smallint unsigned not null comment 'Διάρκεια σε ημέρες',
                `start_date` date not null comment 'Ημερομηνία έναρξης',
                `end_date` date not null comment 'Ημερομηνία λήξης',
                `reason` varchar(200) comment 'Λόγος (για ειδικές κλπ)',
                `comment` longtext not null,
                `create_ts` timestamp not null,
                `update_ts` timestamp not null,
                primary key (`id`)
            ) engine=InnoDB charset=utf8;
          ")
                ->execute();

        $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
        if ($table_schema === null) {
            Console::stdout("Table '{$table_name}' does not appear to exist; considering create unsuccessful.\n", Console::FG_RED);
            return false;
        }

        return true;
    }

}
