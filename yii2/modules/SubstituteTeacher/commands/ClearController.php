<?php

namespace app\modules\SubstituteTeacher\commands;

use yii\helpers\Console;
use yii\console\Controller;
use app\modules\SubstituteTeacher\models\Application;
use app\modules\SubstituteTeacher\models\ApplicationPosition;

/**
 * This command clears redundant data; USE WITH CAUTION!
 * This was created as a helper command during dev/test
 *
 */
class ClearController extends Controller
{

    /**
     * Clear data marked as deleted.
     *
     */
    public function actionIndex()
    {
        if (false === Console::confirm("Clear application data marked with soft delete?")) {
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
}
