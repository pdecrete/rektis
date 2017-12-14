<?php

use yii\helpers\Console;
use yii\db\Migration;

class m160225_121155_rbac_init_admin extends Migration
{

    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // add core roles 
        Console::stdout("Creating core roles [superadmin, admin, user, visitor].\n", Console::FG_YELLOW);
        $superadmin = $auth->createRole('superadmin');
        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');
        $visitor = $auth->createRole('visitor');
        $a1 = $auth->add($superadmin);
        $a2 = $auth->add($admin);
        $a3 = $auth->add($user);
        $a4 = $auth->add($visitor);

        $c1 = $auth->addChild($user, $visitor);
        $c2 = $auth->addChild($admin, $user);
        $c3 = $auth->addChild($superadmin, $admin);

        $b1 = $auth->assign($superadmin, 1);

        return $a1 && $a2 && $a3 && $a4 && $c1 && $c2 && $c3 && $b1;
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        Console::stdout("Clearing all auth data.\n", Console::FG_YELLOW);

        $auth->removeAll();
    }

}
