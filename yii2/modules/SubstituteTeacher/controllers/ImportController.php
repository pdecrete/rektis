<?php
namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\SubstituteTeacher\models\SubstituteTeacherFile;
use app\modules\SubstituteTeacher\models\BaseImportModel;
use app\modules\SubstituteTeacher\models\Position;
use app\modules\SubstituteTeacher\models\Operation;
use app\modules\SubstituteTeacher\models\Prefecture;
use app\models\Specialisation;

/**
 * Description of ImportController
 *
 */
class ImportController extends Controller
{

    /**
     *
     * @var array contains the field mappings for the import files;
     *      Main key is the import identifier 
     *      Secondary keys are column letters and values are thw data associated 
     */
    private $_column_data_idx = [
        'position' => [
            'A' => 'prefecture',
            'B' => 'specialisation',
            'C' => 'title',
            'D' => 'teachers_count',
            'E' => 'hours_count',
            'F' => 'whole_teacher_hours',
        ]
    ];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin', 'spedu_user'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

//    private $workflows = array(
//        'entity' => array(
//            'label' => 'Import entity data',
//            'route' => array('files', 'data' => 'entity'),
//            'description' => 'Import entity data',
//        ),
//        'incident' => array(
//            'label' => 'Incident data import',
//            'route' => array('files', 'data' => 'incident'),
//            'description' => 'Import incident related data',
//        ),

    public function actionIndex()
    {
        $this->redirect(['list-metadata', 'file' => 'lala', 'filter' => 'xyz']);
    }

    /**
     * 
     * @param string $type denotes the import process/datatype
     * @param int $file_id the file identifier
     * @return mixed
     * @throws NotFoundHttpException if the file cannot be found 
     * @throws BadRequestHttpException if the type parameter if not handled
     */
    public function actionFileInformation($type, $file_id)
    {
        switch ($type) {
            case 'position':
                $route = 'import/position';
                break;
            default:
                throw new BadRequestHttpException(Yii::t('substituteteacher', 'The requested import type is not handled.'));
            // break;
        }

        if (($file_model = SubstituteTeacherFile::findOne(['id' => $file_id])) == null) {
            throw new NotFoundHttpException(Yii::t('substituteteacher', 'The requested file does not exist.'));
        }

        $model = new BaseImportModel();
        $model->find($file_model->getFullFilepath());
        return $this->render('file-information', [
                'file_id' => $file_id,
                'model' => $model,
                'route' => $route
        ]);
    }

    public function actionPosition($file_id, $sheet = 0, $action = '', $operation = '')
    {
        if (($file_model = SubstituteTeacherFile::findOne(['id' => $file_id])) == null) {
            throw new NotFoundHttpException(Yii::t('substituteteacher', 'The requested file does not exist.'));
        }

        $model = new BaseImportModel();
        $model->find($file_model->getFullFilepath());

        $model->phpexcelfile->setActiveSheetIndex($sheet);
        $worksheet = $model->phpexcelfile->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $line_limit = min([$highestRow, 50]);
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

        $is_valid = true;
        if ($action == 'validate') {
            $is_valid = $this->validatePosition($operation, $worksheet);
        }

        if ($action == 'import') {
            if (!$this->validatePosition($operation, $worksheet)) {
                return $this->redirect(['position', 'file_id' => $file_id, 'sheet' => $sheet, 'action' => '']);
            }
            Yii::$app->session->removeAllFlashes(); // supress success message 

            if (!$this->importPosition($operation, $worksheet, $highestColumn)) {
                return $this->redirect(['position', 'file_id' => $file_id, 'sheet' => $sheet, 'action' => '']);
            } else {
                return $this->redirect(['position/index']);
            }
        }

        return $this->render('file-preview-position', [
                'action' => $action,
                'sheet' => $sheet,
                'model' => $model,
                'file_id' => $file_id,
                'worksheet' => $worksheet,
                'highestRow' => $highestRow,
                'line_limit' => $line_limit,
                'highestColumn' => $highestColumn,
                'highestColumnIndex' => $highestColumnIndex,
        ]);
    }

    /**
     * 
     * @return boolean whether the import succeeded or not 
     */
    protected function importPosition($operation, $worksheet, $highestColumn)
    {
        $errors = [];
        $stop_at_errorcount = 10; // skip rest of the process if this many errors occur
        $positions = [];
        // keep ids for fks
        $prefectures = [];
        $specialisations = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $row_index = $row->getRowIndex();
            if ($row_index == 1) {
                continue;
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data_row = $worksheet->rangeToArray("A{$row_index}:{$highestColumn}{$row_index}");
            $data = array_combine($this->_column_data_idx['position'], $data_row[0]);

            if (!array_key_exists($data['prefecture'], $prefectures)) {
                $prefecture_model = Prefecture::findOne(['prefecture' => $data['prefecture']]);
                if ($prefecture_model) {
                    $prefectures[$data['prefecture']] = $prefecture_model->id;
                } else {
                    $prefectures[$data['prefecture']] = null; // this will cause a problem later, but we want that
                }
            }
            if (!array_key_exists($data['specialisation'], $specialisations)) {
                $specialisation_model = Specialisation::findOne(['code' => $data['specialisation']]);
                if ($specialisation_model) {
                    $specialisations[$data['specialisation']] = $specialisation_model->id;
                } else {
                    $specialisations[$data['specialisation']] = null; // this will also cause a problem later, but we want that
                }
            }
            // now try to do the trick 
            $position = new Position();
            $position->title = $data['title'];
            $position->operation_id = $operation;
            $position->specialisation_id = $specialisations[$data['specialisation']];
            $position->prefecture_id = $prefectures[$data['prefecture']];
            $position->teachers_count = intval($data['teachers_count']);
            $position->hours_count = intval($data['hours_count']);
            $position->whole_teacher_hours = intval($data['whole_teacher_hours']);
            $position->position_has_type = ($position->teachers_count > 0) ? Position::POSITION_TYPE_TEACHER : Position::POSITION_TYPE_HOURS;

            if (!$position->validate()) {
                $errors[] = $this->extractErrorMessages($position->getErrors());
                if (count($errors) >= $stop_at_errorcount) {
                    break;
                }
            } else {
                $positions[] = $position;
            }
        }

        if (empty($errors)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // TODO clear old data 
                foreach ($positions as $position) {
                    if (!$position->save()) {
                        throw new Exception(Yii::t('substituteteacher', 'An error occured while saving a position.'));
                    }
                }
                $transaction->commit();
                \Yii::$app->session->addFlash('success', Yii::t('substituteteacher', 'Import completed'));
            } catch (\Exception $ex) {
                $transaction->rollBack();
                \Yii::$app->session->addFlash('danger', '<h3>' . Yii::t('substituteteacher', 'Import failed') . '</h3>');
                \Yii::$app->session->addFlash('danger', $ex->getMessage());
            }
        } else {
            \Yii::$app->session->addFlash('danger', '<h3>' . Yii::t('substituteteacher', 'Problems discovered') . '</h3>');
            $never_mind = array_walk($errors, function ($v, $k) {
                \Yii::$app->session->addFlash('danger', $v);
            });
        }

        return empty($errors);
    }

    /**
     * 
     * @return boolean whether the validation succeeded or not 
     */
    protected function validatePosition($operation, $worksheet)
    {
        $errors = [];
        $specialisations = [];
        $prefectures = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $row_index = $row->getRowIndex();
            if ($row_index == 1) {
                continue;
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $teachers_count = 0;
            $hours_count = 0;

            foreach ($cellIterator as $cell) {
                $cell_column = $cell->getColumn();
                // $cell_row = $cell->getRow();

                if (isset($this->_column_data_idx['position'][$cell_column])) {
                    $data_key = $this->_column_data_idx['position'][$cell_column];
                    $$data_key = BaseImportModel::getCalculatedValue($cell);
                    if ($data_key == 'prefecture') {
                        $prefectures[] = BaseImportModel::getCalculatedValue($cell);
                    } elseif ($data_key == 'specialisation') {
                        $specialisations[] = BaseImportModel::getCalculatedValue($cell);
                    }
                }
            }
            // $teachers_count and $hours_count are set via $$data_key 
            if ((int) $teachers_count + (int) $hours_count == 0) {
                $errors[] = Yii::t('substituteteacher', 'There is no information about either teachers or hours for the position at line {n}', ['n' => $row_index]);
            }
        }

        // get unique FK values 
        $prefectures = array_unique($prefectures);
        $specialisations = array_unique($specialisations);
        $located_count_prefectures = \app\modules\SubstituteTeacher\models\Prefecture::find()
            ->andWhere(['prefecture' => $prefectures])
            ->count();
        $located_count_specialisations = \app\models\Specialisation::find()
            ->andWhere(['code' => $specialisations])
            ->count();

        if (($diff = count($prefectures) - $located_count_prefectures) > 0) {
            $errors[] = Yii::t('substituteteacher', '<strong>Could not locate {n,plural,=1{1 prefecture} other{# prefectures}}</strong> out of {m} total.', ['n' => $diff, 'm' => $located_count_prefectures]);
        }
        if (($diff = count($specialisations) - $located_count_specialisations) > 0) {
            $errors[] = Yii::t('substituteteacher', '<strong>Could not locate {n,plural,=1{1 specialisation} other{# specialisations}}</strong> out of {m} total.', ['n' => $diff, 'm' => $located_count_specialisations]);
        }

        if (empty($errors)) {
            \Yii::$app->session->addFlash('success', Yii::t('substituteteacher', 'No apparent problems'));
        } else {
            \Yii::$app->session->addFlash('danger', '<h3>' . Yii::t('substituteteacher', 'Problems discovered') . '</h3>');
            $never_mind = array_walk($errors, function ($v, $k) {
                \Yii::$app->session->addFlash('danger', $v);
            });
        }

        return empty($errors);
    }

    /**
     * 
     * @param array $errors in the form of model errors after validation
     * @return string a concatenated string of the error messages 
     */
    public static function extractErrorMessages($errors)
    {
        return trim(array_reduce(array_values($errors), function ($c, $v) {
                return $c . implode(' ', $v) . ' ';
            }, ''));
    }
}
