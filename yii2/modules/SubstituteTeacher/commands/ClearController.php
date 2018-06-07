<?php

namespace app\modules\SubstituteTeacher\commands;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use app\modules\SubstituteTeacher\models\Application;
use app\modules\SubstituteTeacher\models\ApplicationPosition;
use app\modules\SubstituteTeacher\models\AuditLog;

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
        $total_tasks = 4;
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

        Console::endProgress();

        echo "Check results; entries that would have been deleted:", PHP_EOL;
        echo "- {$audit_log_entries} audit log entries", PHP_EOL;
        echo "- {$applications_deleted} applications marked as deleted", PHP_EOL;
        echo "- {$application_positions_deleted} application positions marked as deleted", PHP_EOL;
        echo "- {$application_positions_orphaned} application positions orhpaned (null application)", PHP_EOL;

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
}
