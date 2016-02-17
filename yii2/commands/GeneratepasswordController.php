<?php

namespace app\commands;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;

/**
 * This command creates a password for a user
 *
 * @author Stavros Papadakis <spapad@gmail.com>
 * @since 2.0
 */
class GeneratepasswordController extends Controller
{

    /**
     * Generate password for string provided. 
     * 
     * @param string $password the text password to be hashed.
     */
    public function actionIndex($password)
    {
        $password_hash = Yii::$app->security->generatePasswordHash($password);
        echo "{$password} = ";
        $this->stdout($password_hash, Console::BOLD);
        echo "\n";
        return Controller::EXIT_CODE_NORMAL;
    }

}
