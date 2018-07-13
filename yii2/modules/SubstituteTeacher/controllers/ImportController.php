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
use app\modules\SubstituteTeacher\models\Prefecture;
use app\models\Specialisation;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\PlacementPreference;
use yii\console\Exception;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use app\modules\SubstituteTeacher\models\TeacherRegistrySpecialisation;
use yii\helpers\Json;

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
            'G' => 'school_type',
            'H' => 'sign_language'
        ],
        'teacher' => [
            'A' => 'vat_number',
            'B' => 'placement_preferences',
            'C' => 'order',
            'D' => 'points',
        ],
        'registry' => [
            'tax_identification_number' => 'B',
            'identity_type' => 'C',
            'identity_number' => 'D',
            'surname' => 'E',
            'firstname' => 'F',
            'fathername' => 'G',
            'degree_categ' => 'H',
            'degree_year' => 'I',
            'degree_mark' => 'J',
            'general_experience_years' => 'K',
            'general_experience_months' => 'L',
            'general_experience_days' => 'M',
            'smeae_experience_years' => 'N',
            'smeae_experience_months' => 'O',
            'smeae_experience_days' => 'P',
            'disability_percentage' => 'Q',
            'disabled_children' => 'R',
            'many_children' => 'S',
            'sign_language' => 'T',
            'academic_criteria_points' => 'U',
            'general_experience_points' => 'V',
            'smeae_experience_points' => 'W',
            'disability_points' => 'X',
            'disabled_children_points' => 'Y',
            'many_children_points' => 'Z',
            'social_criteria_points' => 'AA',
            'total_points' => 'AB',
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
                        'actions' => ['file-information'],
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
            case 'teacher':
                $route = 'import/teacher';
                break;
            case 'registry':
                $route = 'import/registry';
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
        // get file information and set basic parameters
        list($file_model, $model, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex) = $this->prepareImportFile($file_id, $sheet);

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

    
    public function actionRegistry($file_id, $sheet = 0){
        $year = 2018;
        $specialisation_code = 'ΕΒΠ';
        $specialisation_id = \app\modules\SubstituteTeacher\models\Specialisation::findOne(['code' => $specialisation_code])->id; 
        try {
            $transaction = Yii::$app->db->beginTransaction();
            list($file_model, $model, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex) = $this->prepareImportFile($file_id, $sheet);

            $teachersStartRow = $this->teachersStartRowIndex($worksheet);
            $rowIterator = $worksheet->getRowIterator($teachersStartRow);
            
            $existing_teachers = array();
            foreach ($rowIterator as $row) {
                $teacherrowArray = array();
                //$newteachersFlags = array_fill(1, $worksheet->getHighestDataRow() - $teachersStartRow + 1, 0);
                //echo "<pre>"; print_r($newteachersFlags); echo "</pre>"; die();
                $idnum_column = $this->_column_data_idx['registry']['tax_identification_number'];
                 
                $celliterator = $row->getCellIterator();
                foreach ($celliterator as $cell)
                    $teacherrowArray[$cell->getColumn()] = $cell->getFormattedValue();
                //echo "<pre>"; print_r($teacherrowArray); echo "</pre>"; die();
                //echo $teacherrowArray[$idnum_column]; die();
                $registry_teacher = TeacherRegistry::findOne(['tax_identification_number' => $teacherrowArray[$idnum_column]]);
                if(!is_null($registry_teacher)){
                    $existing_teachers[$teacherrowArray[$idnum_column]] = 1;
                }
                else {
                    $existing_teachers[$teacherrowArray[$idnum_column]] = 0;
                    $registry_teacher = new TeacherRegistry();
                }
                $registry_teacher->loadDefaultValues(false);
                $registry_teacher->gender = '';                    
                $registry_teacher->surname = $teacherrowArray[$this->_column_data_idx['registry']['surname']];
                $registry_teacher->firstname = $teacherrowArray[$this->_column_data_idx['registry']['firstname']];
                $registry_teacher->fathername = $teacherrowArray[$this->_column_data_idx['registry']['fathername']];
                $registry_teacher->mothername = '';
                $registry_teacher->marital_status = '';
                $registry_teacher->protected_children = 0;
                $registry_teacher->mobile_phone = '';
                $registry_teacher->home_phone = '';
                $registry_teacher->work_phone = '';
                $registry_teacher->home_address = '';
                $registry_teacher->city = '';
                $registry_teacher->postal_code = '';
                $registry_teacher->social_security_number = '';                    
                $registry_teacher->tax_identification_number = $teacherrowArray[$this->_column_data_idx['registry']['tax_identification_number']];
                $registry_teacher->tax_service = '';
                if($teacherrowArray[$this->_column_data_idx['registry']['identity_type']] == 'ΑΔΤ')
                    $registry_teacher->identity_number = $teacherrowArray[$this->_column_data_idx['registry']['identity_number']];
                else 
                    $registry_teacher->passport_number = $teacherrowArray[$this->_column_data_idx['registry']['identity_number']];
                $registry_teacher->bank = '';
                $registry_teacher->iban = '';
                $registry_teacher->email = '';
                $registry_teacher->birthdate = null;
                $registry_teacher->birthplace = '';
                
                $degree = $this->_column_data_idx['registry']['degree_categ'];
                $registry_teacher->aei = false;
                $registry_teacher->tei = false;
                $registry_teacher->epal = false;
                $registry_teacher->iek = false;
                if(strpos('ΑΕΙ', $degree) || strpos('AEI', $degree)) //written with greek or latin characters
                    $registry_teacher->aei = false;
                if(strpos('ΤΕΙ', $degree) || strpos('TEI', $degree)) //written with greek or latin characters
                    $registry_teacher->tei = true;
                if(strpos('ΕΠΑΛ', $degree) || strpos('TEE', $degree) || strpos('ΤΕΕ', $degree) || strpos('ΤΕΛ', $degree)) 
                    $registry_teacher->epal = true;
                if(strpos('ΙΕΚ', $degree) || strpos('IEK', $degree)) //written with greek or latin characters
                    $registry_teacher->iek = true;
                
                $registry_teacher->military_service_certificate = false;
                if(in_array($teacherrowArray[$this->_column_data_idx['registry']['sign_language']], ['ΟΧΙ', 'ΌΧΙ', 'OXI']))
                    $registry_teacher->sign_language = false;
                else if(in_array($teacherrowArray[$this->_column_data_idx['registry']['sign_language']], ['ΝΑΙ', 'NAI']))
                    $registry_teacher->sign_language = false;
                else
                    throw new Exception('Unknown value in column "ΓΝΩΣΗ ΕΝΓ" for teacher with identity number = ' . $teacherrowArray[$this->_column_data_idx['registry']['identity_number']]);
                
                $registry_teacher->braille = false;
                
                $registry_teacher->specialisation_ids = [$specialisation_id];
                
                if(!$registry_teacher->save())
                    throw new Exception("An error occured while saving teacher with VAT number " . $teacherrowArray['B']);
                    
                $registry_specialization = TeacherRegistrySpecialisation::findOne([ 'registry_id' => $registry_teacher,
                                                                                    'specialisation_id' => $specialisation_id]);
                if($registry_specialization == null)                    
                    $registry_specialization = new TeacherRegistrySpecialisation();
                $registry_specialization->registry_id = $registry_teacher->id;
                $registry_specialization->specialisation_id = $specialisation_id;
                
                if(!$registry_specialization->save())
                    throw new Exception("Error saving in Registry table.");
                    
                $year_teacher = Teacher::findOne(['registry_id' => $registry_teacher->id, 'year' => $year]);
                if($year_teacher == null)
                    $year_teacher = new Teacher();
                $year_teacher->registry_id = $registry_teacher->id;
                $year_teacher->year = $year;
                $year_teacher->status = Teacher::TEACHER_STATUS_ELIGIBLE;
                $year_teacher->public_experience =  $teacherrowArray[$this->_column_data_idx['registry']['general_experience_years']]*365 +
                                                    $teacherrowArray[$this->_column_data_idx['registry']['general_experience_months']]*30 +
                                                    $teacherrowArray[$this->_column_data_idx['registry']['general_experience_days']];
                $year_teacher->smeae_keddy_experience = $teacherrowArray[$this->_column_data_idx['registry']['smeae_experience_years']]*365 +
                                                        $teacherrowArray[$this->_column_data_idx['registry']['smeae_experience_months']]*30 +
                                                        $teacherrowArray[$this->_column_data_idx['registry']['smeae_experience_days']];
                $year_teacher->disability_percentage = str_replace('%', '', $teacherrowArray[$this->_column_data_idx['registry']['disability_percentage']]);
                $year_teacher->disabled_children = $teacherrowArray[$this->_column_data_idx['registry']['disabled_children']];
                
                $year_teacher->three_children = 0;
                $year_teacher->many_children = 0;
                if($teacherrowArray[$this->_column_data_idx['registry']['many_children']] == 'ΠΟΛΥΤΕΚΝΟΣ')
                    $year_teacher->many_children = 1;
                else if($teacherrowArray[$this->_column_data_idx['registry']['many_children']] == 'ΤΡΙΤΕΚΝΟΣ')
                    $year_teacher->three_children = 1;                  

                $json_fields = ['degree_type' => $teacherrowArray[$this->_column_data_idx['registry']['degree_categ']],
                                'degree_date' => $teacherrowArray[$this->_column_data_idx['registry']['degree_year']],
                                'degree_mark' => $teacherrowArray[$this->_column_data_idx['registry']['degree_mark']],
                                'academic_criteria_points' => $teacherrowArray[$this->_column_data_idx['registry']['academic_criteria_points']],
                                'general_experience_points' => $teacherrowArray[$this->_column_data_idx['registry']['general_experience_points']],
                                'smeae_experience_points' => $teacherrowArray[$this->_column_data_idx['registry']['smeae_experience_points']],
                                'disability_points' => $teacherrowArray[$this->_column_data_idx['registry']['disability_points']],
                                'disabled_children_points' => $teacherrowArray[$this->_column_data_idx['registry']['disabled_children_points']],
                                'children_points' => $teacherrowArray[$this->_column_data_idx['registry']['many_children_points']],
                                'social_criteria_points' => $teacherrowArray[$this->_column_data_idx['registry']['social_criteria_points']],
                                'total_points' => $teacherrowArray[$this->_column_data_idx['registry']['total_points']]];
                $json = Json::encode($json_fields);
                $year_teacher->data = $json;
                    
                if(!$year_teacher->save()){                        
                    throw new Exception("Error saving in Teacher table.");
                }

                $teacher_board = TeacherBoard::findOne(['teacher_id' => $year_teacher->id, 'specialisation_id' => $specialisation_id]);
                if($teacher_board == null)
                    $teacher_board = new TeacherBoard();
                $teacher_board->teacher_id = $year_teacher->id;
                $teacher_board->specialisation_id = $specialisation_id;
                $teacher_board->board_type = TeacherBoard::TEACHER_BOARD_TYPE_ANY;
                $teacher_board->points = $teacherrowArray[$this->_column_data_idx['registry']['total_points']];
                $teacher_board->order = 1;
                    
                if(!$teacher_board->save()){
                    //echo "<pre>"; print_r($teacher_board->errors); echo "</pre>"; die();
                    throw new Exception("Error saving in Teacher board.");
                }                
                if($row->getRowIndex() == $worksheet->getHighestDataRow())
                    break;
            }
            $transaction->commit();
            
            $counts = array_count_values($existing_teachers);            
            if(!isset($counts['0']))
                $counts['0'] = 0;
            $feedback = 'Successfully imported ' . $counts['0'] . ' teachers.';            
            if(count($existing_teachers) != $counts['0']){
                $feedback .= '<br />The teachers with tax identity numbers';
                foreach ($existing_teachers as $identity_number => $flag) {
                    if($flag)
                        $feedback .= " '" . $identity_number . "' ";                
                }
                $feedback .= 'were not imported because they already exist in the registry.';
            }

            return $this->render('file-registry-import-feedback', ['feedback' => $feedback]);
        }
        catch(Exception $exc) {
            return $this->render('file-registry-import-feedback', ['feedback' => $exc->getMessage()]);
            $transaction->rollBack();
        }
    }
    
    protected function teachersStartRowIndex($worksheet) {
        foreach ($worksheet->getRowIterator() as $row) {
            $rowArray = array();
            $celliterator = $row->getCellIterator();
            foreach ($celliterator as $cell) {
                $cellvalue = $cell->getValue();                
                if($cell->getColumn() == 'A' && is_numeric($cellvalue) && $cellvalue == 1)
                    return $row->getRowIndex();
                else continue;
            }
        }
    }
    
        
    public function actionTeacher($file_id, $sheet = 0, $action = '', $year = '', $board_type = -1, $specialisation_id = -1)
    {
        // get file information and set basic parameters
        list($file_model, $model, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex) = $this->prepareImportFile($file_id, $sheet);

        $is_valid = true;
        if ($action == 'validate') {
            $is_valid = $this->validateTeacher($year, $board_type, $specialisation_id, $worksheet);
        }

        if ($action == 'import') {
            if (!$this->validateTeacher($year, $board_type, $specialisation_id, $worksheet)) {
                return $this->redirect(['teacher', 'file_id' => $file_id, 'sheet' => $sheet, 'action' => '']);
            }
            Yii::$app->session->removeAllFlashes(); // supress success message

            if (!$this->importTeacher($year, $board_type, $specialisation_id, $worksheet, $highestColumn)) {
                return $this->redirect(['teacher', 'file_id' => $file_id, 'sheet' => $sheet, 'action' => '']);
            } else {
                return $this->redirect(['teacher/index']);
            }
        }

        return $this->render('file-preview-teacher', [
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
     * Get import file model, import handler model and sheet information
     *
     * @param int $file_id The identifier of the import file
     * @param int $sheet The number of the sheet to use
     * @return array $file_model, $model, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex
     */
    protected function prepareImportFile($file_id, $sheet)
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

        return [$file_model, $model, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex];
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

            if (intval($data['school_type']) === Position::SCHOOL_TYPE_KEDDY || $data['school_type'] === 'ΚΕΔΔΥ') {
                $data['school_type'] = Position::SCHOOL_TYPE_KEDDY;
            } else {
                $data['school_type'] = Position::SCHOOL_TYPE_DEFAULT;
            }
            if (intval($data['sign_language']) === Position::SIGN_LANGUAGE_PREFER || $data['sign_language'] === 'ΝΑΙ') {
                $data['sign_language'] = Position::SIGN_LANGUAGE_PREFER;
            } else {
                $data['sign_language'] = Position::SIGN_LANGUAGE_INDIFFERENT;
            }
            // now try to do the trick
            $position = new Position();
            $position->title = $data['title'];
            $position->school_type = intval($data['school_type']);
            $position->operation_id = $operation;
            $position->specialisation_id = $specialisations[$data['specialisation']];
            $position->prefecture_id = $prefectures[$data['prefecture']];
            $position->teachers_count = intval($data['teachers_count']);
            $position->hours_count = intval($data['hours_count']);
            $position->whole_teacher_hours = intval($data['whole_teacher_hours']);
            $position->position_has_type = ($position->teachers_count > 0) ? Position::POSITION_TYPE_TEACHER : Position::POSITION_TYPE_HOURS;
            $position->sign_language = intval($data['sign_language']);

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
                // clear old data; it was checked earlier
                $deletions = Position::deleteAll(['operation_id' => $operation]);

                // add all new positions
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
            $textual_sign_language = empty($sign_language) || ctype_digit($sign_language);
            if (($textual_sign_language && intval($sign_language) !== Position::SIGN_LANGUAGE_PREFER && intval($sign_language) !== Position::SIGN_LANGUAGE_INDIFFERENT)
                || (!$textual_sign_language && $sign_language !== 'ΝΑΙ')) {
                $errors[] = Yii::t('substituteteacher', 'The information for sign language is wrong for the position at line {n}', ['n' => $row_index]);
            }
        }

        // get unique FK values
        $prefectures = array_unique($prefectures);
        $specialisations = array_unique($specialisations);
        $located_count_prefectures = \app\modules\SubstituteTeacher\models\Prefecture::find()
            ->andWhere(['prefecture' => $prefectures])
            ->count();
        $located_count_specialisations = Specialisation::find()
            ->andWhere(['code' => $specialisations])
            ->count();

        if (($diff = count($prefectures) - $located_count_prefectures) > 0) {
            $errors[] = Yii::t('substituteteacher', '<strong>Could not locate {n,plural,=1{1 prefecture} other{# prefectures}}</strong> out of {m} total.', ['n' => $diff, 'm' => $located_count_prefectures]);
        }
        if (($diff = count($specialisations) - $located_count_specialisations) > 0) {
            $errors[] = Yii::t('substituteteacher', '<strong>Could not locate {n,plural,=1{1 specialisation} other{# specialisations}}</strong> out of {m} total.', ['n' => $diff, 'm' => $located_count_specialisations]);
        }

        // also check for used information so as to clear old data only if it has not been already used
        $positions_used = Position::find()
            ->joinWith(['callPositions'], true, 'INNER JOIN')
            ->andWhere([Position::tableName() . '.operation_id' => $operation])
            ->count();
        if ($positions_used > 0) {
            $errors[] = Yii::t('substituteteacher', 'There {n,plural,=1{is 1 position} other{are # positions}} of this operation already involved in a call.', ['n' => $positions_used]);
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
     * Some rules may seem missing, but it is supposed that validateTeacher has already been
     * called before importTeacher (see import action).
     *
     * @param int $year Year inserting teacher to
     * @param int $board_type The teacher board to insert to (@see TeacherBoard)
     * @param int $specialisation_id The teacher board AND teacher specialisation (should match registry and board)
     *
     * @return boolean whether the import succeeded or not
     */
    protected function importTeacher($year, $board_type, $specialisation_id, $worksheet, $highestColumn)
    {
        $errors = [];
        $stop_at_errorcount = 10; // skip rest of the process if this many errors occur
        $teachers = []; // teacher models to save
        $placement_preferences_data = []; // array of placement preferences
        $teacher_board_info = [];
        // keep ids for fks
        $vat_numbers = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $row_index = $row->getRowIndex();
            if ($row_index == 1) {
                continue;
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data_row = $worksheet->rangeToArray("A{$row_index}:{$highestColumn}{$row_index}");
            $data = array_combine($this->_column_data_idx['teacher'], $data_row[0]);

            if (!array_key_exists($data['vat_number'], $vat_numbers)) {
                // $teacher_registry_model = TeacherRegistry::findOne(['tax_identification_number' => $data['vat_number']]);
                $teacher_registry_model = TeacherRegistry::find()
                    ->joinWith('teacherRegistrySpecialisations')
                    ->andWhere([
                        '{{%stteacher_registry}}.tax_identification_number' => $data['vat_number'],
                        '{{%stteacher_registry_specialisation}}.specialisation_id' => $specialisation_id,
                        ])
                    ->one();
                if ($teacher_registry_model) {
                    $vat_numbers[$data['vat_number']] = $teacher_registry_model->id;
                } else {
                    $vat_numbers[$data['vat_number']] = null; // this will cause a problem later, but we want that
                }
            }

            $teacher_registry_id = $vat_numbers[$data['vat_number']];
            $placement_preferences_data = array_merge($placement_preferences_data, array_map(function ($v) use ($teacher_registry_id) {
                return array_merge($v, ['teacher_id' => $teacher_registry_id]);
            }, $this->parsePlacementPreferences($data['placement_preferences'], true)));

            $teacher = new Teacher();
            $teacher->registry_id = $vat_numbers[$data['vat_number']];
            $teacher->year = $year;
            $teacher->status = Teacher::TEACHER_STATUS_ELIGIBLE;

            if (!$teacher->validate()) {
                $errors[] = $this->extractErrorMessages($teacher->getErrors());
                if (count($errors) >= $stop_at_errorcount) {
                    break;
                }
            } else {
                $teachers[] = $teacher;
            }

            // save this for later; teacher registry id, order and points
            $teacher_board_info[] = [
                'teacher_registry_id' => $vat_numbers[$data['vat_number']],
                'order' => $data['order'],
                'points' => $data['points']
            ];
        }

        if (empty($errors)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // should we clear old data? current logic will not lead here if entries exist already
                // $deletions = Teacher::deleteAll(['year' => $year]);

                // add all new teachers
                foreach ($teachers as $teacher) {
                    if (!$teacher->save()) {
                        throw new Exception(Yii::t('substituteteacher', 'An error occured while saving a teacher.'));
                    }
                }

                // find teacher ids
                $year_teacher_info = Teacher::find()
                    ->select(['id', 'registry_id'])
                    ->andWhere(['year' => $year])
                    ->asArray()
                    ->all();
                $year_teacher_ids = [];
                array_walk($year_teacher_info, function ($v, $k) use (&$year_teacher_ids) {
                    $year_teacher_ids[$v['registry_id']] = $v['id'];
                });

                // add teacher to teacher board
                Yii::$app->db->createCommand()->batchInsert(TeacherBoard::tableName(), ['teacher_id', 'specialisation_id', 'board_type', 'points', 'order'], array_map(function ($v) use ($year_teacher_ids, $specialisation_id, $board_type) {
                    return [
                        $year_teacher_ids[$v['teacher_registry_id']], $specialisation_id, $board_type, $v['points'], $v['order']
                    ];
                }, $teacher_board_info))->execute();

                // placement preferences were checked with validateTeacher
                Yii::$app->db->createCommand()->batchInsert(PlacementPreference::tableName(), ['teacher_id', 'prefecture_id', 'school_type', 'order'], array_map(function ($v) use ($year_teacher_ids) {
                    return [
                        $year_teacher_ids[$v['teacher_id']], $v['prefecture_id'], $v['school_type'], $v['order']
                    ];
                }, $placement_preferences_data))->execute();

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
     * Check for:
     * - afm seems valid
     * - afm AND specialisation can be located at the teacher registry
     * - placement preferences seem valid (letter order, etc)
     * - board_type is valid
     *
     * @param int $year Year inserting teacher to
     * @param int $board_type The teacher board to insert to (@see TeacherBoard)
     * @param int $specialisation_id The teacher board AND teacher specialisation (should match registry and board)
     *
     * @return boolean whether the validation succeeded or not
     */
    protected function validateTeacher($year, $board_type, $specialisation_id, $worksheet)
    {
        $errors = [];
        $vat_numbers = [];
        $board_types = array_keys(TeacherBoard::getChoices('board_type'));

        foreach ($worksheet->getRowIterator() as $row) {
            $row_index = $row->getRowIndex();
            if ($row_index == 1) {
                continue;
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                $cell_column = $cell->getColumn();

                if (isset($this->_column_data_idx['teacher'][$cell_column])) {
                    $data_key = $this->_column_data_idx['teacher'][$cell_column];
                    $$data_key = BaseImportModel::getCalculatedValue($cell);
                    if ($data_key == 'vat_number') {
                        $vat_numbers[] = BaseImportModel::getCalculatedValue($cell);
                    } elseif ($data_key == 'placement_preferences') {
                        // no need to check ordering because it is created by the import process...
                        // check validity of selections though
                        $placement_preferences_import = $this->parsePlacementPreferences($placement_preferences, true);
                        if ($placement_preferences_import === false) {
                            $errors[] = Yii::t('substituteteacher', 'Problem with placement preference <em>{str}</em>.', ['str' => $placement_preferences]);
                        } else {
                            $placement_preferences_data = array_map(function ($v) {
                                $model = new PlacementPreference(array_merge(['id' => 0], $v));
                                return $model;
                            }, $placement_preferences_import);
                        }
                        if (($valid = PlacementPreference::checkRules($placement_preferences_data)) !== true) {
                            foreach ($placement_preferences_data as $error_model) {
                                if ($error_model->hasErrors()) {
                                    $errors[] = $this->extractErrorMessages($error_model->getErrors());
                                }
                            }
                            $errors[] = Yii::t('substituteteacher', 'Problem with placement preference <em>{str}</em>.', ['str' => $placement_preferences]);
                        }
                    }
                }
            }
        }

        // get unique FK values
        $vat_numbers = array_unique($vat_numbers);
        $located_count_vat_numbers = TeacherRegistry::find()
            ->joinWith('teacherRegistrySpecialisations')
            ->andWhere([
                '{{%stteacher_registry}}.tax_identification_number' => $vat_numbers,
                '{{%stteacher_registry_specialisation}}.specialisation_id' => $specialisation_id,
                ])
            ->count();

        if (($diff = count($vat_numbers) - $located_count_vat_numbers) > 0) {
            $errors[] = Yii::t('substituteteacher', '<strong>Could not locate {n,plural,=1{1 tax identification number and specialisation combination} other{# tax identification numbers and specialisation combinations}}</strong> out of {m} total.', ['n' => $diff, 'm' => count($vat_numbers)]);
        }

        // check if there are entries already in the requested year; don't mind about teacher boards, all will be deleted
        if ($year > 1900) {
            $located_count_teachers_in_year = TeacherRegistry::find()
                ->joinWith('teachers')
                ->andWhere([
                    'tax_identification_number' => $vat_numbers,
                    Teacher::tableName() . '.year' => $year
                ])
                ->count();
            if ($located_count_teachers_in_year > 0) {
                $errors[] = Yii::t('substituteteacher', '<strong>{n,plural,=1{1 teacher} other{# teachers}}</strong> are <strong>already included</strong> in the year {y} lists.', ['n' => $located_count_teachers_in_year, 'y' => $year]);
            }
        }

        // check if board type is valid
        if (!in_array($board_type, $board_types, true)) {
            $errors[] = Yii::t('substituteteacher', 'Board type is not valid.');
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
     * Get a string of placement preferences (i.e. "ΗΡΧ", "ΗΚ ΗΣ ΡΚ,ΡΣ")
     * and check it's validity.
     * Return parsed preferences, ready to use in active record, if wanted.
     *
     * @return boolean|array If false, an error occured, if true valid; array is returned when input is valid and $return_parsed === true
     */
    protected function parsePlacementPreferences($placement_preferences, $return_parsed = false)
    {
        $placement_preferences_parsed = [];

        $prefectures = Prefecture::defaultSelectables('symbol', 'id', null); // id => symbol
        $prefectures_symbols = array_keys($prefectures);
        $prefecture_symbols = implode('', $prefectures_symbols);
        $school_symbols = PlacementPreference::SCHOOL_TYPE_SCHOOL_SYMBOL . PlacementPreference::SCHOOL_TYPE_KEDDY_SYMBOL;

        $placement_preferences = preg_replace("/[^{$prefecture_symbols}{$school_symbols}]/u", ' ', $placement_preferences);
        $placement_preferences = preg_split("/\s/u", $placement_preferences, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        if (count($placement_preferences) > 0) {
            $order = 1;
            foreach ($placement_preferences as $placement_preference) {
                $matches = [];
                if (mb_strlen($placement_preference) === 2 && preg_match("/[{$school_symbols}]/u", $placement_preference) === 1) {
                    // manipulate preference
                    $matches = preg_split('//u', $placement_preference, null, PREG_SPLIT_NO_EMPTY);
                    if (in_array($matches[0], $prefectures_symbols, true)) {
                        if ($return_parsed === true) {
                            $placement_preferences_parsed[] = [
                                'prefecture_id' => $prefectures[$matches[0]],
                                'school_type' => ($matches[1] === PlacementPreference::SCHOOL_TYPE_SCHOOL_SYMBOL ? PlacementPreference::SCHOOL_TYPE_SCHOOL : PlacementPreference::SCHOOL_TYPE_KEDDY),
                                'order' => $order++
                            ];
                        }
                    } else {
                        $placement_preferences_parsed = false;
                        break;
                    }
                } else {
                    // add prefecture as preference for any kind of school
                    $matches = array_filter(preg_split('//u', $placement_preference, null, PREG_SPLIT_NO_EMPTY), function ($v) {
                        return !empty($v);
                    });
                    foreach ($matches as $pref_match) {
                        if (in_array($pref_match, $prefectures_symbols, true)) {
                            if ($return_parsed === true) {
                                $placement_preferences_parsed[] = [
                                    'prefecture_id' => $prefectures[$pref_match],
                                    'school_type' => PlacementPreference::SCHOOL_TYPE_ANY,
                                    'order' => $order++
                                ];
                            }
                        } else {
                            $placement_preferences_parsed = false;
                            break;
                        }
                    }
                }
            }
        }

        return ($return_parsed === true) ? $placement_preferences_parsed : ($placement_preferences_parsed !== false);
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
