<?php

namespace app\modules\SubstituteTeacher\commands;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use app\modules\SubstituteTeacher\models\Application;
use app\modules\SubstituteTeacher\models\ApplicationPosition;
use app\modules\SubstituteTeacher\models\AuditLog;
use app\modules\SubstituteTeacher\models\PlacementTeacher;
use app\modules\SubstituteTeacher\models\Placement;
use app\modules\SubstituteTeacher\models\PlacementPosition;
use app\modules\SubstituteTeacher\models\PlacementPrint;
use app\modules\SubstituteTeacher\models\OperationSpecialisation;

/**
 * This command clears redundant data; USE WITH CAUTION!
 * This was created as a helper command during dev/test
 *
 */
class ClearController extends Controller
{
    public $defaultAction = 'check';

    public function actionCheck()
    {
        $total_tasks = 7;
        $current_task = 0;
        Console::startProgress($current_task++, $total_tasks, "Checking: ");

        $applications_deleted = Application::find()
            ->where(['[[deleted]]' => Application::APPLICATION_DELETED])
            ->count();
        Console::updateProgress($current_task++, $total_tasks);

        $application_positions_deleted = ApplicationPosition::find()
            ->where(['[[deleted]]' => ApplicationPosition::APPLICATION_POSITION_DELETED])
            ->count();
        Console::updateProgress($current_task++, $total_tasks);

        $application_positions_orphaned = ApplicationPosition::find()
            ->where(['[[application_id]]' => null])
            ->count();
        Console::updateProgress($current_task++, $total_tasks);

        $audit_log_entries = AuditLog::find()->count();
        Console::updateProgress($current_task++, $total_tasks);

        $placement_prints_deleted = PlacementPrint::find()
            ->where(['[[deleted]]' => PlacementPrint::PRINT_DELETED])
            ->count();
        Console::updateProgress($current_task++, $total_tasks);

        $placements_deleted_ids = array_map(function ($m) {
            return $m->id;
        }, $placements_qry->all());
        $placements_positions_deleted = PlacementPosition::find()
            ->where(['[[placement_teacher_id]]' => $placements_deleted_ids])
            ->count();
        Console::updateProgress($current_task++, $total_tasks);

        $operation_specialisations_orphaned = OperationSpecialisation::find()
            ->where(['[[operation_id]]' => null])
            ->count();
        Console::updateProgress($current_task++, $total_tasks);

        Console::endProgress();

        echo "Check results; entries that would have been deleted:", PHP_EOL;
        echo "- {$audit_log_entries} audit log entries", PHP_EOL;
        echo "- {$applications_deleted} applications marked as deleted", PHP_EOL;
        echo "- {$application_positions_deleted} application positions marked as deleted", PHP_EOL;
        echo "- {$application_positions_orphaned} application positions orhpaned (null application)", PHP_EOL;
        echo "- {$placements_positions_deleted} placement positions linked to placements marked as deleted", PHP_EOL;
        echo "- {$placement_prints_deleted} placement prints marked as deleted", PHP_EOL;
        echo "- {$operation_specialisations_orphaned} operation specialisations orphaned", PHP_EOL;

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Clear ALL teacher relates data (teacher, teacher registry, teacher boards, preferences, applications, placements)
     */
    public function actionCleanTeachers()
    {
        if (false === Console::confirm(Console::ansiFormat("Clear all teacher related data?", [Console::BG_RED]))) {
            echo "Abort.\n";
            exit();
        }

        $t_s = Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();

        $t01 = Yii::$app->db->createCommand()->truncateTable('{{%stapplication}}')->execute();
        $t02 = Yii::$app->db->createCommand()->truncateTable('{{%stapplication_position}}')->execute();
        $t03 = Yii::$app->db->createCommand()->truncateTable('{{%stplacement}}')->execute();
        $t04 = Yii::$app->db->createCommand()->truncateTable('{{%stplacement_position}}')->execute();
        $t05 = Yii::$app->db->createCommand()->truncateTable('{{%stplacement_preference}}')->execute();
        $t06 = Yii::$app->db->createCommand()->truncateTable('{{%stplacement_print}}')->execute();
        $t07 = Yii::$app->db->createCommand()->truncateTable('{{%stplacement_teacher}}')->execute();
        $t08 = Yii::$app->db->createCommand()->truncateTable('{{%stteacher}}')->execute();
        $t09 = Yii::$app->db->createCommand()->truncateTable('{{%stteacher_board}}')->execute();
        $t10 = Yii::$app->db->createCommand()->truncateTable('{{%stteacher_registry}}')->execute();
        $t11 = Yii::$app->db->createCommand()->truncateTable('{{%stteacher_registry_specialisation}}')->execute();
        $t12 = Yii::$app->db->createCommand()->truncateTable('{{%stteacher_status_audit}}')->execute();

        $t_e = Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();

        echo "Done. Please check resluts.", PHP_EOL;

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Clear application data that has been marked as deleted or is orphaned.
     *
     */
    public function actionApplication()
    {
        if (false === Console::confirm(Console::ansiFormat("Clear application data marked with soft delete or orphaned?", [Console::BG_RED]))) {
            echo "Abort.\n";
            exit();
        }

        $total_tasks = 3;
        $current_task = 0;
        Console::startProgress($current_task++, $total_tasks, "Clearing: ");

        $applications_deleted = Application::deleteAll(['[[deleted]]' => Application::APPLICATION_DELETED]);
        Console::updateProgress($current_task++, $total_tasks);

        $application_positions_deleted = ApplicationPosition::deleteAll(['[[deleted]]' => ApplicationPosition::APPLICATION_POSITION_DELETED]);
        Console::updateProgress($current_task++, $total_tasks);

        $application_positions_orphaned = ApplicationPosition::deleteAll(['[[application_id]]' => null]);
        Console::updateProgress($current_task++, $total_tasks);

        Console::endProgress();

        echo "Cleared entries:", PHP_EOL;
        echo "- {$applications_deleted} applications marked as deleted", PHP_EOL;
        echo "- {$application_positions_deleted} application positions marked as deleted", PHP_EOL;
        echo "- {$application_positions_orphaned} application positions orhpaned (null application)", PHP_EOL;

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Clear module audit log.
     *
     */
    public function actionAudit()
    {
        if (false === Console::confirm(Console::ansiFormat("Clear module audit log?", [Console::BG_RED]))) {
            echo "Abort.\n";
            exit();
        }

        $truncate = Yii::$app->db->createCommand()->truncateTable('{{%staudit_log}}')->execute();

        echo "Cleared audit log [{$truncate}]", PHP_EOL;

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Clear placement prints that has been marked as deleted. Also remove corresponing files.
     *
     */
    public function actionPlacementPrints()
    {
        if (false === Console::confirm(Console::ansiFormat("Clear placement prints marked with soft delete AND delete corresponding files?", [Console::BG_RED]))) {
            echo "Abort.\n";
            exit();
        }

        $placement_prints = PlacementPrint::find()
            ->where(['[[deleted]]' => PlacementPrint::PRINT_DELETED])
            ->all();
        $deleted_files = 0;
        array_walk($placement_prints, function ($model, $idx) use (&$deleted_files) {
            $filename = PlacementPrint::getFilenameAbspath($model->filename, 'export');
            if (is_file($filename)) {
                @unlink($filename);
                $deleted_files++;
            }
        });

        $placement_prints_deleted = PlacementPrint::deleteAll(['[[deleted]]' => PlacementPrint::PRINT_DELETED]);
        echo "Cleared entries:", PHP_EOL;
        echo "- {$placement_prints_deleted} placement prints marked as deleted", PHP_EOL;
        echo "- {$deleted_files} linked files deleted", PHP_EOL;

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Clear operation-specialisation mappings that has been orphaned.
     *
     */
    public function actionOperationSpecialisations()
    {
        if (false === Console::confirm(Console::ansiFormat("Clear operation-specialisation mappings that have been orphaned?", [Console::BG_RED]))) {
            echo "Abort.\n";
            exit();
        }

        $operation_specialisations_orphaned = OperationSpecialisation::deleteAll(['[[operation_id]]' => null]);

        echo "Cleared entries:", PHP_EOL;
        echo "- {$operation_specialisations_orphaned} operation specialisations orphaned", PHP_EOL;

        return Controller::EXIT_CODE_NORMAL;
    }

}
