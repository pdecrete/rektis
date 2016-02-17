
create table if not exists `admapp_user` (
    `id` integer not null auto_increment,
    `username` varchar(128) not null,
    `auth_key` varchar(32) not null,
    `password_hash` varchar(200) not null,
    `password_reset_token` varchar(200) not null,
    `email` varchar(128) not null,
    `name` varchar(128) not null,
    `surname` varchar(128) not null,
    `status` smallint not null default 1,
    `last_login` timestamp comment 'last sucessful login',
    `create_ts` timestamp not null,
    `update_ts` timestamp not null,
    primary key (`id`),
    unique index admapp_user_username (`username`),
    unique index admapp_user_password_token (`password_reset_token`)
) engine=InnoDB charset=utf8;

create table if not exists `admapp_specialisation` (
    `id` integer not null auto_increment,
    `code` varchar(10) not null comment 'κωδικός ειδικότητας',
    `name` varchar(100) not null comment 'λεκτικό',
    primary key (`id`),
    unique index specialisation_code (`code`)
) engine=InnoDB charset=utf8;

create table if not exists `admapp_service` (
    `id` integer not null auto_increment,
    `name` varchar(100) not null,
    `information` varchar(500) not null,
    primary key (`id`),
    unique index service_name (`name`)
) engine=InnoDB charset=utf8 comment='Υπηρεσιες';

create table if not exists `admapp_position` (
    `id` integer not null auto_increment,
    `name` varchar(100) not null,
    `comments` text not null,
    primary key (`id`),
    unique index position_name (`name`)
) engine=InnoDB charset=utf8;

create table admapp_employee_status (
    `id` integer not null auto_increment,
    `name` varchar(100) not null,
    primary key (`id`),
    unique index status_name (`name`)
) engine=InnoDB charset=utf8;

/*
drop table if exists `admapp_employee`;
create table if not exists `admapp_employee` (
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
    primary key (`id`)
) engine=InnoDB charset=utf8;

*/

create table if not exists `admapp_employee` (
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

/*
    `anatr_katataksi` int(11) not null COMMENT 'se hmeres',
    `anatr` int(11) not null COMMENT 'se hmeres',
    `anatr_excel` int(11) not null COMMENT 'se hmeres',
    `eidikh` tinyint(1) not null,

    `aney` int(11) not null,
    `aney_xr` int(11) not null COMMENT 'Συνολ. χρόνος αδ. άνευ αποδοχών',
    `aney_apo` date not null COMMENT 'Αδ. ανευ από',
    `aney_ews` date not null COMMENT 'Αδ. άνευ έως',
    `updated` timestamp not null DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `wres` int(11) not null COMMENT 'Διδακτικές ώρες (βάσει ετών υπηρεσίας)',
    `idiwtiko` tinyint(4) not null,
    `idiwtiko_id` tinyint(4) not null,
    `idiwtiko_liksi` date not null,
    `idiwtiko_enarxi` date not null,
    `idiwtiko_id_enarxi` date not null,
    `idiwtiko_id_liksi` date not null,
    `katoikon` tinyint(4) not null,
    `katoikon_apo` date not null,
    `katoikon_ews` date not null,
    `katoikon_comm` text not null,
    KEY `klados` (`klados`),
    KEY `sx_organikhs` (`sx_organikhs`)

*/
