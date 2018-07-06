<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180504_080006_rbac_schooltransport_users extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        Console::stdout("Creating roles for \"School Transports\" module: \"School Transport Director\",
                        \"School Transport Editor\", \"School Transport Viewer\"\n");

        $schtransport_viewer = $auth->createRole('schtransport_viewer');
        $schtransport_editor = $auth->createRole('schtransport_editor');
        $schtransport_director = $auth->createRole('schtransport_director');

        $schtr_v = $auth->add($schtransport_viewer);
        $schtr_e = $auth->add($schtransport_editor);
        $schtr_d = $auth->add($schtransport_director);

        $admin = $auth->getRole('admin');
        $visitor = $auth->getRole('visitor');

        $schtr_v_child = $auth->addChild($schtransport_viewer, $visitor);
        $schtr_e_child = $auth->addChild($schtransport_editor, $schtransport_viewer);
        $schtr_d_child = $auth->addChild($schtransport_director, $schtransport_editor);
        $admin_child = $auth->addChild($admin, $schtransport_director);

        return $schtr_v && $schtr_e && $schtr_d && $schtr_v_child && $schtr_e_child && $schtr_d_child && $admin_child;
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        Console::stdout("Deleting roles for \"School Transports\" module: \"School Transport Director\",
                        \"School Transport Editor\", \"School Transport Viewer\"\n");

        $admin = $auth->getRole('admin');
        $visitor = $auth->getRole('visitor');
        $schtransport_viewer = $auth->getRole('schtransport_viewer');
        $schtransport_editor = $auth->getRole('schtransport_editor');
        $schtransport_director = $auth->getRole('schtransport_director');

        $rmv_chld1 = $auth->removeChild($schtransport_viewer, $visitor);
        $rmv_chld2 = $auth->removeChild($schtransport_editor, $schtransport_viewer);
        $rmv_chld3 = $auth->removeChild($schtransport_director, $schtransport_editor);
        $rmv_chld4 = $auth->removeChild($admin, $schtransport_director);

        $rmv_viewer = $auth->remove($schtransport_viewer);
        $rmv_editor = $auth->remove($schtransport_editor);
        $rmv_director = $auth->remove($schtransport_director);

        return $rmv_chld1 && $rmv_chld2 && $rmv_chld3 && $rmv_chld4 && $rmv_viewer && $rmv_editor && $rmv_director;
    }
}
