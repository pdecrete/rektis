<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180201_075605_rbac_financial_users extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        
        Console::stdout("Creating roles for \"Finance\" module: \"Financial Director\", 
                        \"Financial Editor\", \"Financial Director\", \"Financial Viewer\"");
        
        $financial_viewer = $auth->createRole('financial_viewer');
        $financial_editor = $auth->createRole('financial_editor');
        $financial_director = $auth->createRole('financial_director');
        
        $fv = $auth->add($financial_viewer);
        $fe = $auth->add($financial_editor);
        $fd = $auth->add($financial_director);
        
        $admin = $auth->getRole('admin');
        $visitor = $auth->getRole('visitor');
        
        $fv_child = $auth->addChild($financial_viewer, $visitor);
        $fe_child = $auth->addChild($financial_editor, $financial_viewer);
        $fd_child = $auth->addChild($financial_director, $financial_editor);
        $admin_child = $auth->addChild($admin, $financial_director);

        
        return $fv && $fe && $fd && $fv_child && $fe_child && $fd_child && $admin_child;
        
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        
        Console::stdout("Deleting roles for \"Finance\" module: \"Financial Director\",
                        \"Financial Editor\", \"Financial Director\", \"Financial Viewer\"");
        
        $admin = $auth->getRole('admin');
        $visitor = $auth->getRole('visitor');
        $financial_viewer = $auth->getRole('financial_viewer');
        $financial_editor = $auth->getRole('financial_editor');
        $financial_director = $auth->getRole('financial_director');
        
        $rmv_chld1 = $auth->removeChild($financial_viewer, $visitor);
        $rmv_chld2 = $auth->removeChild($financial_editor, $financial_viewer);
        $rmv_chld3 = $auth->removeChild($financial_director, $financial_editor);
        $rmv_chld4 = $auth->removeChild($admin, $financial_director);

        $rmv_viewer = $auth->remove($financial_viewer);
        $rmv_editor = $auth->remove($financial_editor);
        $rmv_director = $auth->remove($financial_director);
        
        return $rmv_chld2 && $rmv_chld3 && $rmv_chld4 && $rmv_viewer && $rmv_editor && $rmv_director;
    }
}
