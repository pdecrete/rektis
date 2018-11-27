<?php

use yii\db\Migration;
use yii\helpers\Console;

class m181023_102437_rbac_disposal_users extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        Console::stdout("Creating roles for \"Disposal\" module: \"Disposal Director\",
                        \"Disposal Editor\", \"Disposal Viewer\"\n");

        $disposal_viewer = $auth->createRole('disposal_viewer');
        $disposal_editor = $auth->createRole('disposal_editor');
        $disposal_director = $auth->createRole('disposal_director');

        $disposal_v = $auth->add($disposal_viewer);
        $disposal_e = $auth->add($disposal_editor);
        $disposal_d = $auth->add($disposal_director);

        $admin = $auth->getRole('admin');
        $visitor = $auth->getRole('visitor');

        $disposal_v_child = $auth->addChild($disposal_viewer, $visitor);
        $disposal_e_child = $auth->addChild($disposal_editor, $disposal_viewer);
        $disposal_d_child = $auth->addChild($disposal_director, $disposal_editor);
        $admin_child = $auth->addChild($admin, $disposal_director);

        return $disposal_v && $disposal_e && $disposal_d && $disposal_v_child && $disposal_e_child && $disposal_d_child && $admin_child;
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        Console::stdout("Deleting roles for \"Disposal\" module: \"Disposal Director\",
                        \"Disposal Editor\", \"Disposal Viewer\"\n");

        $admin = $auth->getRole('admin');
        $visitor = $auth->getRole('visitor');
        $disposal_viewer = $auth->getRole('disposal_viewer');
        $disposal_editor = $auth->getRole('disposal_editor');
        $disposal_director = $auth->getRole('disposal_director');

        $rmv_chld1 = $auth->removeChild($disposal_viewer, $visitor);
        $rmv_chld2 = $auth->removeChild($disposal_editor, $disposal_viewer);
        $rmv_chld3 = $auth->removeChild($disposal_director, $disposal_editor);
        $rmv_chld4 = $auth->removeChild($admin, $disposal_director);

        $rmv_viewer = $auth->remove($disposal_viewer);
        $rmv_editor = $auth->remove($disposal_editor);
        $rmv_director = $auth->remove($disposal_director);

        return $rmv_chld1 && $rmv_chld2 && $rmv_chld3 && $rmv_chld4 && $rmv_viewer && $rmv_editor && $rmv_director;
    }
}
