<?php

use yii\db\Migration;

class m160316_212056_reset_mysql_ts extends Migration
{

    public function up()
    {
        Yii::$app->db->createCommand("alter table `admapp_user`
                    change `last_login`
                    `last_login` timestamp not null default CURRENT_TIMESTAMP comment 'last sucessful login'"
                )
                ->execute();
    }

    public function down()
    {
        echo "Nothing to do for reverting migration...\n";
    }

}
