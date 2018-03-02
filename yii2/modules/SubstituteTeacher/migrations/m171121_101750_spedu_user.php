<?php

use yii\db\Migration;
use yii\helpers\Console;

class m171121_101750_spedu_user extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // add core roles
        Console::stdout("Creating role for substitute teacher module functions [spedu_user].\n", Console::FG_YELLOW);
        $spedu_user = $auth->createRole('spedu_user');
        $role = $auth->add($spedu_user);

        $admin = $auth->getRole('admin');
        $visitor = $auth->getRole('visitor');

        $a1 = $auth->addChild($spedu_user, $visitor);
        $a2 = $auth->addChild($admin, $spedu_user);

        return $role && $a1 && $a2;
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        Console::stdout("Clearing role for substitute teacher module functions [spedu_user].\n", Console::FG_YELLOW);
        $admin = $auth->getRole('admin');
        $visitor = $auth->getRole('visitor');
        $spedu_user = $auth->getRole('spedu_user');

        $a1 = $auth->removeChild($spedu_user, $visitor);
        $a2 = $auth->removeChild($admin, $spedu_user);

        $b1 = $auth->remove($spedu_user);

        return $a1 && $a2 && $b1;
    }
}
