<?php

use yii\helpers\Console;
use yii\db\Migration;
use yii\db\Expression;
use admapp\Util\Core;
use app\models\User;

class m160302_083430_rbac_init_demo_user extends Migration
{

    public function safeUp()
    {
        $pass = admapp\Util\Core::generateToken(10);
        $encpass = Yii::$app->security->generatePasswordHash($pass);

        Console::stdout("Creating core fake user (fakeuser:{$pass}).\n", Console::FG_YELLOW);
        Yii::$app->db->createCommand()->insert("{$this->db->tablePrefix}user", [
            'id' => 2,
            'username' => 'fakeuser',
            'auth_key' => '',
            'password_hash' => $encpass,
            'password_reset_token' => Core::generateToken(10),
            'email' => 'spapad+2@gmail.com',
            'name' => 'Faker',
            'surname' => 'Fakeruser',
            'status' => User::STATUS_ACTIVE, // 10
            'last_login' => null,
            'create_ts' => new Expression('CURRENT_TIMESTAMP'),
            'update_ts' => new Expression('CURRENT_TIMESTAMP')
        ])->execute();

        Console::stdout("Assigning fake user role.\n", Console::FG_YELLOW);
        $auth = Yii::$app->authManager;
        $user = $auth->getRole('user');
        $aok = $auth->assign($user, 2);

        return $aok;
    }

    public function safeDown()
    {
        Console::stdout("Revoking fake user role.\n", Console::FG_YELLOW);
        $auth = Yii::$app->authManager;
        $user = $auth->getRole('user');
        $aok = $auth->revoke($user, 2);

        Console::stdout("Deleting core fake user.\n", Console::FG_YELLOW);
        Yii::$app->db->createCommand()->delete("{$this->db->tablePrefix}user", 'id = :id', [':id' => 2])->execute();

        return $aok;
    }

}
