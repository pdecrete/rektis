<?php

use yii\db\Migration;
use yii\helpers\Console;

class m181023_101435_rbac_eduinventory_users extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        
        Console::stdout("Creating roles for \"Education Inventory\" module: \"Education Inventory Director\",
                        \"Education Inventory Editor\", \"Education Inventory Viewer\"\n");
        
        $eduinventory_viewer = $auth->createRole('eduinventory_viewer');
        $eduinventory_editor = $auth->createRole('eduinventory_editor');
        $eduinventory_director = $auth->createRole('eduinventory_director');
        
        $edinvtr_v = $auth->add($eduinventory_viewer);
        $edinvtr_e = $auth->add($eduinventory_editor);
        $edinvtr_d = $auth->add($eduinventory_director);
        
        $admin = $auth->getRole('admin');
        $visitor = $auth->getRole('visitor');
        
        $edinvtr_v_child = $auth->addChild($eduinventory_viewer, $visitor);
        $edinvtr_e_child = $auth->addChild($eduinventory_editor, $eduinventory_viewer);
        $edinvtr_d_child = $auth->addChild($eduinventory_director, $eduinventory_editor);
        $admin_child = $auth->addChild($admin, $eduinventory_director);
        
        return $edinvtr_v && $edinvtr_e && $edinvtr_d && $edinvtr_v_child && $edinvtr_e_child && $edinvtr_d_child && $admin_child;
    }
    
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        
        Console::stdout("Deleting roles for \"Education Inventory\" module: \"Education Inventory Director\",
                        \"Education Inventory Editor\", \"Education Inventory Viewer\"\n");
        
        $admin = $auth->getRole('admin');
        $visitor = $auth->getRole('visitor');
        $eduinventory_viewer = $auth->getRole('eduinventory_viewer');
        $eduinventory_editor = $auth->getRole('eduinventory_editor');
        $eduinventory_director = $auth->getRole('eduinventory_director');
        
        $rmv_chld1 = $auth->removeChild($eduinventory_viewer, $visitor);
        $rmv_chld2 = $auth->removeChild($eduinventory_editor, $eduinventory_viewer);
        $rmv_chld3 = $auth->removeChild($eduinventory_director, $eduinventory_editor);
        $rmv_chld4 = $auth->removeChild($admin, $eduinventory_director);
        
        $rmv_viewer = $auth->remove($eduinventory_viewer);
        $rmv_editor = $auth->remove($eduinventory_editor);
        $rmv_director = $auth->remove($eduinventory_director);
        
        return $rmv_chld1 && $rmv_chld2 && $rmv_chld3 && $rmv_chld4 && $rmv_viewer && $rmv_editor && $rmv_director;
    }
}
