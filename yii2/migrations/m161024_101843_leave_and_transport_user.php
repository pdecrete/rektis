<?php

use yii\helpers\Console;
use yii\db\Migration;

class m161024_101843_leave_and_transport_user extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // add core roles 
        Console::stdout("Creating core roles [leave_user, transport_user].\n", Console::FG_YELLOW);
        $leave_user = $auth->createRole('leave_user');
        $transport_user = $auth->createRole('transport_user');
        $a1 = $auth->add($leave_user);
        $a2 = $auth->add($transport_user);
		
		$admin = $auth->getRole('admin');
		$visitor = $auth->getRole('visitor');
		
        $b1 = $auth->addChild($leave_user, $visitor);
        $b2 = $auth->addChild($transport_user, $visitor);
        $b3 = $auth->addChild($admin, $leave_user);
        $b4 = $auth->addChild($admin, $transport_user);

        return $a1 && $a2 && $b1 && $b2 && $b3 && $b4;
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        Console::stdout("Clearing all auth data concerning [leave_user, transport_user].\n", Console::FG_YELLOW);
		$admin = $auth->getRole('admin');
		$visitor = $auth->getRole('visitor');
		$leave_user = $auth->getRole('leave_user');
		$transport_user = $auth->getRole('transport_user');

        $a1 = $auth->removeChild($leave_user, $visitor);
        $a2 = $auth->removeChild($transport_user, $visitor);
        $a3 = $auth->removeChild($admin, $leave_user);
        $a4 = $auth->removeChild($admin, $transport_user);

        $b1 = $auth->remove($leave_user);
        $b2 = $auth->remove($transport_user);
        
        return $a1 && $a2 && $a3 && $a4 && $b1 && $b2;
    }
}
