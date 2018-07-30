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
use app\modules\SubstituteTeacher\models\TeacherBoard;
use app\modules\SubstituteTeacher\models\TeacherRegistrySpecialisation;
use yii\helpers\Json;
use yii\base\NotSupportedException;

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
        'placement-preference' => [
            'A' => 'vat_number',
            'B' => 'placement_preferences',
        ],
        'registry' => [
            'default' => [
                'tax_identification_number' => 'B',
                'identity_type' => 'C',
                'identity_number' => 'D',
                'passport_number' => 'D',
                'surname' => 'E',
                'firstname' => 'F',
                'fathername' => 'G',
                'specialisation' => 'H',
                'degree_categ' => 'I',
                'pedagogical_competence_required' => 'J',
                'pedagogical_competence' => 'K',
                'teacher_board_type' => 'L',
                'degree_year' => 'M',
                'degree_mark' => 'N',
                'has_doctorate' => 'O',
                'has_master' => 'P',
                'has_doctorate_special_education' => 'Q',
                'has_master_special_education' => 'R',
                'general_experience_years' => 'S',
                'general_experience_months' => 'T',
                'general_experience_days' => 'U',
                'smeae_experience_years' => 'V',
                'smeae_experience_months' => 'W',
                'smeae_experience_days' => 'X',
                'disability_percentage' => 'Y',
                'disabled_children' => 'Z',
                'many_children' => 'AA',
                'braille' => 'AB',
                'sign_language' => 'AC',
                'degree_points' => 'AD',
                'master_doctorate_spoecialisation_points' => 'AE',
                'master_doctorate_special_education_points' => 'AF',
                'master_doctorate_points' => 'AG',
                'general_experience_points' => 'AH',
                'smeae_experience_points' => 'AI',
                'disability_points' => 'AJ',
                'disabled_children_points' => 'AK',
                'many_children_points' => 'AL',
                'social_criteria_points' => 'AM',
                'total_points' => 'AN',
            ],
            'ebp' => [
                'tax_identification_number' => 'B',
                'identity_type' => 'C',
                'identity_number' => 'D',
                'passport_number' => 'D',
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
            case 'placement-preference':
                $route = 'import/placement-preference';
                break;
            case 'update-teacher':
                $route = 'import/generic-update-teacher';
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

    /**
     * 
     * @return boolean|null True if value is a YES, False if value is a NO, 
     *      $default_on_empty (default false) if empty value, 
     *      $default_on_other (default null) if none of the previous conditions apply
     */
    protected function isYesNo($value, $default_on_empty = false, $default_on_other = null)
    {
        if (empty($value)) {
            return $default_on_empty;
        } elseif (in_array($value, ['ΟΧΙ', 'ΌΧΙ', 'OXI'], true)) {
            return false;
        } elseif (in_array($value, ['ΝΑΙ', 'NAI'], true)) {
            return true;
        } else {
            return $default_on_other;
        }
    }

    protected function importRegistry($year, $board_type, $specialisation_id, $worksheet)
    {
        $import_status = false;

        try {
            // decide import data sequencing 
            $ebp_spec = Specialisation::findOne(['code' => 'ΕΒΠ']);
            if (empty($ebp_spec)) {
                throw new \Exception(Yii::t('substituteteacher', 'Cannot locate necessary specialisation id.'));
            }

            if ($ebp_spec->id == $specialisation_id) {
                $attribute_map = $this->_column_data_idx['registry']['ebp'];
            } else {
                $attribute_map = $this->_column_data_idx['registry']['default'];
            }

            $transaction = Yii::$app->db->beginTransaction();
            $highestDataRow = $worksheet->getHighestDataRow(); 
            $teachersStartRow = $this->teachersStartRowIndex($worksheet);
            $rowIterator = $worksheet->getRowIterator($teachersStartRow);

            $existing_teachers = [];
            foreach ($rowIterator as $row) {
                $row_index = $row->getRowIndex();
                $teacherrowArray = [];
                $idnum_column = $attribute_map['tax_identification_number'];

                $celliterator = $row->getCellIterator();
                foreach ($celliterator as $cell) {
                    $teacherrowArray[$cell->getColumn()] = $cell->getFormattedValue();
                }
                $registry_teacher = TeacherRegistry::findOne(['tax_identification_number' => $teacherrowArray[$idnum_column]]);
                if (!is_null($registry_teacher)) {
                    $existing_teachers[$teacherrowArray[$idnum_column]] = 1;
                } else {
                    $existing_teachers[$teacherrowArray[$idnum_column]] = 0;
                    $registry_teacher = new TeacherRegistry();
                    $registry_teacher->loadDefaultValues(false);
                    $registry_teacher->gender = '';
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
                    $registry_teacher->tax_service = '';
                    $registry_teacher->bank = '';
                    $registry_teacher->iban = '';
                    $registry_teacher->email = '';
                    $registry_teacher->birthdate = null;
                    $registry_teacher->birthplace = '';
                    $registry_teacher->comments = '';
                }
                $registry_teacher->surname = $teacherrowArray[$attribute_map['surname']];
                $registry_teacher->firstname = $teacherrowArray[$attribute_map['firstname']];
                $registry_teacher->fathername = $teacherrowArray[$attribute_map['fathername']];
                $registry_teacher->tax_identification_number = $teacherrowArray[$attribute_map['tax_identification_number']];
                if ($teacherrowArray[$attribute_map['identity_type']] == 'ΑΔΤ') {
                    $registry_teacher->identity_number = $teacherrowArray[$attribute_map['identity_number']];
                } else {
                    $registry_teacher->passport_number = $teacherrowArray[$attribute_map['passport_number']];
                }

                $degree = $teacherrowArray[$attribute_map['degree_categ']];
                $registry_teacher->aei = false;
                $registry_teacher->tei = false;
                $registry_teacher->epal = false;
                $registry_teacher->iek = false;
                if (mb_strpos($degree, 'ΑΕΙ') !== false || strpos($degree, 'AEI') !== false) { //written with greek or latin characters
                    $registry_teacher->aei = true;
                }
                if (mb_strpos($degree, 'ΤΕΙ') !== false || strpos($degree, 'TEI') !== false) { //written with greek or latin characters
                    $registry_teacher->tei = true;
                }
                if (mb_strpos($degree, 'ΕΠΑΛ') !== false || strpos($degree, 'TEE') !== false || mb_strpos($degree, 'ΤΕΕ') !== false || mb_strpos($degree, 'ΤΕΛ') !== false) {
                    $registry_teacher->epal = true;
                }
                if (mb_strpos($degree, 'ΙΕΚ') !== false || strpos($degree, 'IEK') !== false) { //written with greek or latin characters
                    $registry_teacher->iek = true;
                }

                $registry_teacher->military_service_certificate = false;

                $registry_teacher->sign_language = $this->isYesNo($teacherrowArray[$attribute_map['sign_language']]);
                if ($registry_teacher->sign_language === null) {
                    throw new \Exception(Yii::t('substituteteacher', 'Unknown value in column "{col}" for teacher with identity number {id}.', ['col' => 'ΓΝΩΣΗ ΕΝΓ', 'id' => $teacherrowArray[$attribute_map['identity_number']]]));
                }

                if (array_key_exists('braille', $attribute_map)) {
                    $registry_teacher->braille = $this->isYesNo($teacherrowArray[$attribute_map['braille']]);
                    if ($registry_teacher->braille === null) {
                        throw new \Exception(Yii::t('substituteteacher', 'Unknown value in column "{col}" for teacher with identity number {id}.', ['col' => 'ΓΝΩΣΗ BRAILLE', 'id' => $teacherrowArray[$attribute_map['identity_number']]]));
                    }
                } else {
                    $registry_teacher->braille = false;
                }

                $registry_teacher->specialisation_ids = [$specialisation_id];

                if (!$registry_teacher->save()) {
                    throw new \Exception(
                        "An error occured while saving teacher with VAT number {$teacherrowArray[$idnum_column]}" .
                        array_reduce(array_values($registry_teacher->getErrors()), function ($c, $v) {
                            return $c . implode(' ', $v) . ' ';
                        }, '')
                    );
                } else {
                    $registry_teacher->refresh();
                }

                $registry_specialization = TeacherRegistrySpecialisation::findOne([
                    'registry_id' => $registry_teacher->id,
                    'specialisation_id' => $specialisation_id
                ]);
                if ($registry_specialization == null) {
                    $registry_specialization = new TeacherRegistrySpecialisation();
                    $registry_specialization->registry_id = $registry_teacher->id;
                    $registry_specialization->specialisation_id = $specialisation_id;
                    if (!$registry_specialization->save()) {
                        throw new \Exception("Error saving in Registry table.");
                    }
                } else {
                    \Yii::$app->session->addFlash('info', "found {$registry_teacher->id}/{$specialisation_id}");
                }

                $year_teacher = Teacher::findOne([
                    'registry_id' => $registry_teacher->id,
                    'year' => $year
                ]);
                if ($year_teacher == null) {
                    $year_teacher = new Teacher();
                    $year_teacher->registry_id = $registry_teacher->id;
                    $year_teacher->year = $year;
                }
                $year_teacher->status = Teacher::TEACHER_STATUS_ELIGIBLE;
                $year_teacher->public_experience =  $teacherrowArray[$attribute_map['general_experience_years']]*365 +
                                                    $teacherrowArray[$attribute_map['general_experience_months']]*30 +
                                                    $teacherrowArray[$attribute_map['general_experience_days']];
                $year_teacher->smeae_keddy_experience = $teacherrowArray[$attribute_map['smeae_experience_years']]*365 +
                                                        $teacherrowArray[$attribute_map['smeae_experience_months']]*30 +
                                                        $teacherrowArray[$attribute_map['smeae_experience_days']];
                $year_teacher->disability_percentage = empty($teacherrowArray[$attribute_map['disability_percentage']]) ? 0 : str_replace('%', '', $teacherrowArray[$attribute_map['disability_percentage']]);
                $year_teacher->disabled_children = empty($teacherrowArray[$attribute_map['disabled_children']]) ? 0 : $teacherrowArray[$attribute_map['disabled_children']];

                $year_teacher->three_children = 0;
                $year_teacher->many_children = 0;
                if ($teacherrowArray[$attribute_map['many_children']] == 'ΠΟΛΥΤΕΚΝΟΣ') {
                    $year_teacher->many_children = 1;
                } elseif ($teacherrowArray[$attribute_map['many_children']] == 'ΤΡΙΤΕΚΝΟΣ') {
                    $year_teacher->three_children = 1;
                }

                $json_fields = array_fill_keys([
                    // common
                    'degree_categ',
                    'degree_year',
                    'degree_mark',
                    'general_experience_points',
                    'smeae_experience_points',
                    'disability_points',
                    'disabled_children_points',
                    'many_children_points',
                    'social_criteria_points',
                    'total_points',
                    // default
                    'has_doctorate',
                    'has_master',
                    'has_doctorate_special_education',
                    'has_master_special_education',
                    'degree_points',
                    'master_doctorate_spoecialisation_points',
                    'master_doctorate_special_education_points',
                    'master_doctorate_points',
                    // ebp 
                    'academic_criteria_points',
                ], null);

                array_walk($json_fields, function (&$value, $key) use ($teacherrowArray, $attribute_map) {
                    if (array_key_exists($key, $attribute_map)) {
                        $value = $teacherrowArray[$attribute_map[$key]];
                    }
                });
                $json = Json::encode($json_fields);
                $year_teacher->data = $json;

                if (!$year_teacher->save()) {
                    throw new \Exception(
                        "Error saving in Teacher table." . 
                        array_reduce(array_values($year_teacher->getErrors()), function ($c, $v) {
                            return $c . implode(' ', $v) . ' ';
                        }, '')
                    );
                } else {
                    $year_teacher->refresh();
                }

                $teacher_board = TeacherBoard::findOne([
                    'teacher_id' => $year_teacher->id, 
                    'specialisation_id' => $specialisation_id
                ]);
                if ($teacher_board == null) {
                    $teacher_board = new TeacherBoard();
                }
                $teacher_board->teacher_id = $year_teacher->id;
                $teacher_board->specialisation_id = $specialisation_id;
                $teacher_board->board_type = $board_type;
                $teacher_board->points = $teacherrowArray[$attribute_map['total_points']];
                $teacher_board->order = 1 + $row_index - $teachersStartRow;

                if (!$teacher_board->save()) {
                    throw new \Exception("Error saving in Teacher board.");
                }

                if ($row_index == $highestDataRow) {
                    break;
                }
            }
            $transaction->commit();

            $counts = array_count_values($existing_teachers);
            if (!isset($counts['0'])) {
                $counts['0'] = 0;
            }

            \Yii::$app->session->addFlash('info', Yii::t('substituteteacher', 'Successfully imported {n} teachers.', ['n' => $counts['0']]));
            if (count($existing_teachers) != $counts['0']) {
                $existing_teachers_ids = array_keys(array_filter($existing_teachers, function ($flag) { return $flag == 1; }));
                \Yii::$app->session->addFlash('info', Yii::t('substituteteacher', 'The teachers with tax identity numbers <em>{ids}</em> were not imported because they already exist in the registry.', ['ids' => implode(', ', $existing_teachers_ids)]));
            }
            $import_status = true;
        } catch (\Exception $exc) {
            $transaction->rollBack();
            \Yii::$app->session->addFlash('danger', $exc->getMessage());
            $import_status = false;
        }
        return $import_status;
    }

    public function actionRegistry($file_id, $sheet = 0, $action = '', $year = '', $board_type = -1, $specialisation_id = -1)
    {
        list($file_model, $model, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex) = $this->prepareImportFile($file_id, $sheet);
        $highestDataRow = $worksheet->getHighestDataRow(); 
        $teachersStartRow = $this->teachersStartRowIndex($worksheet);

        if ($teachersStartRow <= 0) {
            \Yii::$app->session->addFlash('danger', Yii::t('substituteteacher', 'There seems to be no data in the worksheet.'));
        } else {
            // only import action for now 
            if ($action == 'import') {
                $year = filter_var($year, FILTER_SANITIZE_NUMBER_INT);
                $board_type = filter_var($board_type, FILTER_SANITIZE_NUMBER_INT);
                $specialisation_id = filter_var($specialisation_id, FILTER_SANITIZE_NUMBER_INT);
                // TODO check input 
                if (empty($year) || empty($board_type) || empty($specialisation_id)) {
                    \Yii::$app->session->addFlash('danger', Yii::t('substituteteacher', 'Year, board type and specialisation are mandatory.'));
                } else {
                    $import_ok = $this->importRegistry($year, $board_type, $specialisation_id, $worksheet);
                    if ($import_ok === true) {
                        return $this->redirect(['teacher/index']);
                    } else {
                        return $this->redirect(['registry', 'file_id' => $file_id, 'sheet' => $sheet, 'action' => '']);
                    }
                }
            }
        }

        return $this->render('file-preview-registry', [
            'action' => $action,
            'sheet' => $sheet,
            'model' => $model,
            'file_id' => $file_id,
            'worksheet' => $worksheet,
            'highestRow' => $highestRow,
            'line_limit' => $line_limit + $teachersStartRow - 1,
            'highestColumn' => $highestColumn,
            'highestColumnIndex' => $highestColumnIndex,
            'teachersStartRow' => $teachersStartRow - 1,
            'hasData' => $teachersStartRow > 0
        ]);
    }

    protected function teachersStartRowIndex($worksheet)
    {
        foreach ($worksheet->getRowIterator() as $row) {
            $rowArray = [];
            $celliterator = $row->getCellIterator();
            foreach ($celliterator as $cell) {
                $cellvalue = $cell->getValue();
                if ($cell->getColumn() == 'A' && is_numeric($cellvalue) && $cellvalue == 1) {
                    return $row->getRowIndex();
                } else {
                    continue;
                }
            }
        }
    }

    /**
     * This process expects to find headers on first line
     * and data on the following lines. 
     * 
     * @return boolean whether the import succeeded or not
     */
    protected function importGenericUpdateTeacher($key_field, $supported_fields_wlabels, $supported_fields_types, $year, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex)
    {
        $errors = [];
        $stop_at_errorcount = 10; // skip rest of the process if this many errors occur
        $update_models = [];

        // get headers and check for validity 
        $headers = $worksheet->rangeToArray("A1:{$highestColumn}1");
        $headers_row = reset($headers);
        $invalid_fields = array_filter($headers_row, function ($v) use ($supported_fields_wlabels) {
            return !in_array($v, $supported_fields_wlabels) && !array_key_exists($v, $supported_fields_wlabels);
        });
        if (!empty($invalid_fields)) {
            \Yii::$app->session->addFlash('danger', Yii::t('substituteteacher', 'The following fields are not supported or recognised: {fields}', ['fields' => implode(', ', $invalid_fields)]));
            return false;
        }

        $mapped_fields = array_map(function ($v) use ($supported_fields_wlabels) {
            if (in_array($v, $supported_fields_wlabels)) {
                return $v;
            } elseif (array_key_exists($v, $supported_fields_wlabels)) {
                return $supported_fields_wlabels[$v];
            } else {
                return null;
            }
        }, $headers_row);

        // check index key existance 
        if (!in_array($key_field, $mapped_fields)) {
            \Yii::$app->session->addFlash('danger', Yii::t('substituteteacher', 'The identification key is not amongst the provided information.'));
            return false;
        }

        foreach ($worksheet->getRowIterator(2) as $row) {
            $row_index = $row->getRowIndex();

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data_row = $worksheet->rangeToArray("A{$row_index}:{$highestColumn}{$row_index}");
            $data = array_combine($mapped_fields, $data_row[0]);

            // Check updated teacher for existance 
            $teacherModel = TeacherRegistry::findOne([$key_field => $data[$key_field]]);
            if (empty($teacherModel)) {
                $errors[] = Yii::t('substituteteacher', 'The teacher with identification {id} could not be located in the registry.', ['id' => $data[$key_field]]);
                if (count($errors) >= $stop_at_errorcount) {
                    break;
                }
            };

            // Prepare or manipulate any data to be updated 
            $data = array_filter($data, function ($v) {
                return trim($v) !== ''; // skip empty cells
            });
            array_walk($data, function (&$v, $k) use ($supported_fields_types) {
                if (array_key_exists($k, $supported_fields_types)) {
                    switch ($supported_fields_types[$k]) {
                        case 'date':
                            // date: if not empty, try to get standard representation Y-m-d
                            $backup_v = $v;
                            $v = trim($v);
                            if (!empty($v)) {
                                $v = strtotime($v);
                            }
                            if (empty($v)) {
                                $v = $backup_v;
                            } else {
                                $v = date('Y-m-d', $v);
                            }
                            break;
                        default:
                            break;
                    }
                }
            });
            
            // possibly move this to model; set safe attributes for specific batch update scenario and use setAttributes
            $teacherModel->setAttributes($data, false);
            if (!$teacherModel->validate(array_keys($data))) {
                $errors[] = Yii::t('substituteteacher', 'There were errors validating the update information for teacher {id}: {msgs}', ['id' => $data[$key_field], 'msgs' => $this->extractErrorMessages($teacherModel->getErrors())]);
                if (count($errors) >= $stop_at_errorcount) {
                    break;
                }
            } else {
                $update_models[] = [
                    'model' => $teacherModel,
                    'attributes' => array_keys($data)
                ];
            }
        }

        if (empty($errors)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($update_models as $update_model) {
                    if (!$update_model['model']->save(false, $update_model['attributes'])) {
                        throw new \Exception(Yii::t('substituteteacher', 'An error occured while updating teacher {id}.', ['id' => $update_model['model']->tax_identification_number]));
                    }
                }
                $transaction->commit();
                \Yii::$app->session->addFlash('success', Yii::t('substituteteacher', 'Update completed'));
            } catch (\Exception $ex) {
                $transaction->rollBack();
                \Yii::$app->session->addFlash('danger', '<h3>' . Yii::t('substituteteacher', 'Update failed') . '</h3>');
                \Yii::$app->session->addFlash('danger', $ex->getMessage());
            }
        } else {
            \Yii::$app->session->addFlash('danger', '<h3>' . Yii::t('substituteteacher', 'Problems discovered') . '</h3>');
            array_walk($errors, function ($v, $k) {
                \Yii::$app->session->addFlash('danger', $v);
            });
        }

        return empty($errors);
    }

    /**
     * Update fields from the teacher registry table. 
     * 
     */
    public function actionGenericUpdateTeacher($file_id, $sheet = 0, $action = '', $year = '')
    {
        $key_field = 'tax_identification_number';
        $supported_fields = [
            'gender',
            'surname',
            'firstname',
            'fathername',
            'mothername',
            'marital_status',
            'protected_children',
            'mobile_phone',
            'home_phone',
            'work_phone',
            'home_address',
            'city',
            'postal_code',
            'social_security_number',
            'tax_identification_number',
            'tax_service',
            'identity_number',
            'bank',
            'iban',
            'email',
            'birthdate',
            'birthplace',
            'aei',
            'tei',
            'epal',
            'iek',
            'military_service_certificate',
            'sign_language',
            'braille',
            'passport_number',
            'ama',
            'efka_facility',
            'municipality'
        ];
        // for field that need some manipulation 
        $supported_fields_types = [
            'birthdate' => 'date'
        ];
        $baseModel = new TeacherRegistry();
        $supported_fields_wlabels = array_combine(array_map(function ($v) use ($baseModel) {
            return $baseModel->getAttributeLabel($v);
        }, $supported_fields), $supported_fields);
        
        list($file_model, $model, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex) = $this->prepareImportFile($file_id, $sheet);
        if ($highestRow <= 1) {
            \Yii::$app->session->addFlash('danger', Yii::t('substituteteacher', 'There seems to be no data in the worksheet.'));
        } else {
            // only import action for now 
            if ($action == 'import') {
                $year = filter_var($year, FILTER_SANITIZE_NUMBER_INT);
                if (empty($year)) {
                    \Yii::$app->session->addFlash('danger', Yii::t('substituteteacher', 'Year is mandatory.'));
                } else {
                    $import_ok = $this->importGenericUpdateTeacher($key_field, $supported_fields_wlabels, $supported_fields_types, $year, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex);
                    if ($import_ok === true) {
                        return $this->redirect(['teacher/index']);
                    } else {
                        return $this->redirect(['generic-update-teacher', 'file_id' => $file_id, 'sheet' => $sheet, 'action' => '']);
                    }
                }
            }
        }

        return $this->render('file-preview-generic-update-teacher', [
            'action' => $action,
            'sheet' => $sheet,
            'model' => $model,
            'file_id' => $file_id,
            'worksheet' => $worksheet,
            'highestRow' => $highestRow,
            'line_limit' => $line_limit,
            'highestColumn' => $highestColumn,
            'highestColumnIndex' => $highestColumnIndex,
            'hasData' => $highestRow > 1,
            'key_field' => $key_field,
            'supported_fields_wlabels' => $supported_fields_wlabels
        ]);
    }

    /**
     * Assigns teacher placement preferences for existing teachers. 
     * 
     */
    public function actionPlacementPreference($file_id, $sheet = 0, $action = '', $year = '', $specialisation_id = -1)
    {
        list($file_model, $model, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex) = $this->prepareImportFile($file_id, $sheet);

        $is_valid = true;
        if ($action == 'validate') {
            $is_valid = $this->validateTeacher($this->_column_data_idx['placement-preference'], $year, TeacherBoard::TEACHER_BOARD_TYPE_ANY, $specialisation_id, $worksheet);
        }

        if ($action == 'import') {
            if (!$this->validateTeacher($this->_column_data_idx['placement-preference'], $year, TeacherBoard::TEACHER_BOARD_TYPE_ANY, $specialisation_id, $worksheet, ['existing-teacher'])) {
                return $this->redirect(['placement-preference', 'file_id' => $file_id, 'sheet' => $sheet, 'action' => '']);
            }
            Yii::$app->session->removeAllFlashes(); // supress messages

            if (!$this->importPlacementPreferences($year, $specialisation_id, $worksheet, $highestColumn)) {
                return $this->redirect(['placement-preference', 'file_id' => $file_id, 'sheet' => $sheet, 'action' => '']);
            } else {
                return $this->redirect(['teacher/index']);
            }
        }

        return $this->render('file-preview-placement-preference', [
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
     * @param int $year Year teacher should be already inserted to
     * @param int $specialisation_id The teacher board AND teacher specialisation (should match registry and board)
     *
     * @return boolean whether the import succeeded or not
     */
    protected function importPlacementPreferences($year, $specialisation_id, $worksheet, $highestColumn)
    {
        $errors = [];
        $stop_at_errorcount = 1; // skip rest of the process if this many errors occur
        $placement_preferences_data = []; // array of placement preferences
        // keep ids for fks
        $vat_numbers = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $row_index = $row->getRowIndex();
            if ($row_index == 1) {
                continue;
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $data = [];
            foreach ($cellIterator as $cell) {
                $data[$this->_column_data_idx['placement-preference'][$cell->getColumn()]] = $cell->getFormattedValue();
            }

            if (!array_key_exists($data['vat_number'], $vat_numbers)) {
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

            $teacher = Teacher::findOne([
                'registry_id' => $vat_numbers[$data['vat_number']],
                'year' => $year
            ]);
            if (empty($teacher)) {
                $errors[] = Yii::t('substituteteacher', 'Could not locate teacher {id} entry in year {y}.', ['id' => $vat_numbers[$data['vat_number']], 'y' => $year]);
                if (count($errors) >= $stop_at_errorcount) {
                    break;
                }
            }
        }

        if (empty($errors)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
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

                // clear any existing placement preferences information for this teacher
                PlacementPreference::deleteAll(['teacher_id' => array_values($year_teacher_ids)]);

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
                \Yii::$app->session->addFlash('danger', print_r($ex->getLine(), true));
            }
        } else {
            \Yii::$app->session->addFlash('danger', '<h3>' . Yii::t('substituteteacher', 'Problems discovered') . '</h3>');
            $never_mind = array_walk($errors, function ($v, $k) {
                \Yii::$app->session->addFlash('danger', $v);
            });
            \Yii::$app->session->addFlash('danger', 'ccccccc');
        }

        return empty($errors);
    }

    /**
     * Obsolete
     */
    public function actionTeacher($file_id, $sheet = 0, $action = '', $year = '', $board_type = -1, $specialisation_id = -1)
    {
        throw new NotSupportedException();

        // get file information and set basic parameters
        list($file_model, $model, $worksheet, $highestRow, $line_limit, $highestColumn, $highestColumnIndex) = $this->prepareImportFile($file_id, $sheet);

        $is_valid = true;
        if ($action == 'validate') {
            $is_valid = $this->validateTeacher($this->_column_data_idx['teacher'], $year, $board_type, $specialisation_id, $worksheet);
        }

        if ($action == 'import') {
            if (!$this->validateTeacher($this->_column_data_idx['teacher'], $year, $board_type, $specialisation_id, $worksheet)) {
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
        $highestRow = $worksheet->getHighestDataRow();
        $line_limit = min([$highestRow, 50]);
        $highestColumn = $worksheet->getHighestDataColumn();
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
                        throw new \Exception(Yii::t('substituteteacher', 'An error occured while saving a position.'));
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
                        throw new \Exception(Yii::t('substituteteacher', 'An error occured while saving a teacher.'));
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
     * @param array $attribute_map The attributes => column mapping i.e. $this->_column_data_idx['teacher']
     * @param int $year Year inserting teacher to
     * @param int $board_type The teacher board to insert to (@see TeacherBoard)
     * @param int $specialisation_id The teacher board AND teacher specialisation (should match registry and board)
     * @param array $do_not_validate skip any of these checks: ['teacher-board', 'existing-teacher', 'tax-id-specialisation-combination']
     *
     * @return boolean whether the validation succeeded or not
     */
    protected function validateTeacher($attribute_map, $year, $board_type, $specialisation_id, $worksheet, $do_not_validate = [])
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

                if (isset($attribute_map[$cell_column])) {
                    $data_key = $attribute_map[$cell_column];
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

        if (!in_array('tax-id-specialisation-combination', $do_not_validate)) {
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
        }

        if (!in_array('existing-teacher', $do_not_validate, true)) {
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
        }

        if (!in_array('teacher-board', $do_not_validate, true)) {
            // check if board type is valid
            if (!in_array($board_type, $board_types, true)) {
                $errors[] = Yii::t('substituteteacher', 'Board type is not valid.');
            }
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
