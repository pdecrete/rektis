<?php

namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use app\modules\SubstituteTeacher\models\Prefecture;
use app\modules\SubstituteTeacher\models\CallPosition;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\Call;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use app\modules\SubstituteTeacher\models\TeacherRegistrySpecialisation;
use yii\db\Expression;
use app\modules\SubstituteTeacher\models\PlacementPreference;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use app\modules\SubstituteTeacher\models\Position;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use yii\helpers\Json;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\Application;
use app\modules\SubstituteTeacher\models\ApplicationPosition;

class BridgeController extends \yii\web\Controller
{
    private $client = null; // http client for calls to the applications frontend
    private $options = [];

    public function init()
    {
        $this->options = array_merge($this->options, [
            'baseUrl' => $this->module->params['applications-baseurl'],
            // 'transport' => 'yii\httpclient\CurlTransport',
            // 'timeout' => 20,
            'requestConfig' => [
                'format' => Client::FORMAT_JSON
            ],
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
        $this->client = new Client($this->options);
    }

    protected function getHeaders()
    {
        return [
            'Authorization' => "Bearer " . $this->module->params['applications-key']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'remote-status' => ['GET', 'POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['remote-status', 'receive', 'send', 'fetch'],
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

    public function actionRemoteStatus()
    {
        $data = null;
        $status = null;
        $services_status = [
            'applications' => false,
            'load' => false,
            'clear' => false,
            'unload' => false,
        ];

        if (\Yii::$app->request->isPost) {
            $status_response = $this->client->post('index', null, $this->getHeaders())->send();
            $status = $status_response->isOk ? $status_response->isOk : $status_response->statusCode;
            $data = $status_response->getData();
            $services_status = array_merge($services_status, $data['services']);
        }
        $connection_options = $this->options;

        \Yii::info(\Yii::$app->request->isPost, __METHOD__);
        return $this->render('remote-status', compact('status', 'services_status', 'data', 'connection_options'));
    }

    /**
     * Choose a call to receive application information from the frontend.
     *
     * @param int|null call_id
     */
    public function actionReceive($call_id = 0)
    {
        \Yii::info([], __METHOD__);
        $connection_options = $this->options;
        $data = null;
        $message_unload = '';
        $status_unload = null;
        $status_data = false;
        $messages_data = [];

        $call_model = Call::findOne(['id' => $call_id]);
        // if call is selected, collect positions, prefectures, teachers and placement preferences
        if (!empty($call_model)) {
            if (\Yii::$app->request->isPost) {
                \Yii::info(['Call [unload] with [post] method', $connection_options], __METHOD__);
                $status_response = $this->client->post('unload', $data, $this->getHeaders())->send();
                $status_unload = $status_response->isOk ? $status_response->isOk : $status_response->statusCode;
                $response_data_unload = $status_response->getData();
                // dd($response_data_unload);
                if ($status_unload !== true) {
                    \Yii::error([$status_unload, $response_data_unload], __METHOD__);
                } else {
                    \Yii::info([$status_unload, $response_data_unload], __METHOD__);
                    $message_unload = $response_data_unload['message'];

                    $transaction = Yii::$app->db->beginTransaction();
                    // check input for malformed information and save accordingly
                    try {
                        // get incoming data
                        $applicants = isset($response_data_unload['data']['applicants']) ? $response_data_unload['data']['applicants'] : [];
                        $choices = isset($response_data_unload['data']['choices']) ? $response_data_unload['data']['choices'] : [];
                        $applications_parsed = isset($response_data_unload['data']['applications']) ? $response_data_unload['data']['applications'] : [];

                        // get references to frontend ids used to link data
                        $applicants_parsed = array_flip(array_map(function ($v) {
                            return $v['id'];
                        }, $applicants));
                        $choices_parsed = array_flip(array_map(function ($v) {
                            return $v['id'];
                        }, $choices));

                        // decrypt data
                        array_walk($applicants, function ($v, $k) use (&$applicants_parsed) {
                            $applicants_parsed[$v['id']] = [];
                            foreach (['reference', 'vat', 'identity', 'specialty', 'agreedterms', 'application_choices', 'state', 'statets'] as $field) {
                                $applicants_parsed[$v['id']][$field] = \Yii::$container->get('Crypt')->decrypt($v[$field]);
                            }
                            $applicants_parsed[$v['id']]['reference'] = Json::decode($applicants_parsed[$v['id']]['reference']);
                            return;
                        });
                        array_walk($choices, function ($v, $k) use (&$choices_parsed) {
                            $choices_parsed[$v['id']] = [
                                'reference' => Json::decode(\Yii::$container->get('Crypt')->decrypt($v['reference'])),
                            ];
                            return;
                        });

                        $messages_data[] = Yii::t('substituteteacher', '{n} applicants parsed.', ['n' => count($applicants_parsed)]);
                        $messages_data[] = Yii::t('substituteteacher', '{n} choices parsed.', ['n' => count($choices_parsed)]);
                        $messages_data[] = Yii::t('substituteteacher', '{n} applications parsed.', ['n' => count($applications_parsed)]);

                        // check data integrity:
                        // - frontend application references to applicants and choices
                        // - backend references to applicants and teacher board
                        // - backend references to choices
                        array_walk($applications_parsed, function ($v) use ($applicants_parsed, $choices_parsed) {
                            // check each entry for valid references to applicant and choice selection
                            if (!isset($applicants_parsed[$v['applicant_id']])) {
                                throw new \Exception(Yii::t('substituteteacher', 'Invalid reference to applicant.'));
                            }
                            if (!isset($choices_parsed[$v['choice_id']])) {
                                throw new \Exception(Yii::t('substituteteacher', 'Invalid reference to choice.'));
                            }
                        });
                        array_walk($choices_parsed, function ($v) {
                            // check if choice is valid in backend
                            if (is_array($v['reference']['id'])) {
                                $ids = $v['reference']['id'];
                            } else {
                                $ids = [$v['reference']['id']];
                            }
                            $choices = CallPosition::find()->andWhere(['id' => $ids])->count(); // TODO add group info lookup?
                            if (count($ids) != $choices) {
                                throw new \Exception(Yii::t('substituteteacher', 'Invalid reference to call position.'));
                            }
                        });
                        array_walk($applicants_parsed, function (&$v) {
                            $teacher = Teacher::find()->joinWith('registry')->andWhere([
                                Teacher::tableName() . '.id' => $v['reference']['id'],
                                TeacherRegistry::tableName() . '.tax_identification_number' => $v['vat'],
                                TeacherRegistry::tableName() . '.identity_number' => $v['identity'],
                                TeacherRegistry::tableName() . '.firstname' => $v['reference']['firstname'],
                                TeacherRegistry::tableName() . '.surname' => $v['reference']['lastname'],
                            ])->one();
                            if (empty($teacher)) {
                                throw new \Exception(Yii::t('substituteteacher', 'Invalid reference to teacher.'));
                            }

                            // select appropriate teacher board
                            $boards = array_filter($teacher->boards, function ($m) use ($v) {
                                return $m->specialisation->id == $v['reference']['specialty_id'];
                            });
                            if (empty($boards)) {
                                throw new \Exception(Yii::t('substituteteacher', 'Could not locate teacher board related to teacher specialisation.'));
                            } else {
                                $first_board = reset($boards);
                                $v['reference']['teacher_board_id'] = $first_board->id;
                            }
                        });

                        //
                        // TODO CHECK IF THERE IS DATA FOR THIS CALL!!! WARN USER!!!
                        //

                        // mark previous data as deleted; just do this once
                        $deletions = Application::updateAll([
                            'deleted' => Application::APPLICATION_DELETED,
                            'updated_at' => new Expression('NOW()')
                        ], [
                            'call_id' => $call_model->id,
                            'deleted' => Application::APPLICATION_NOT_DELETED
                        ]); 

                        // add new applications
                        array_walk($applicants_parsed, function (&$v, $key_applicant_id) use ($call_model, $applications_parsed, $choices_parsed) {
                            $application = new Application;
                            $application->call_id = $call_model->id;
                            $application->teacher_board_id = $v['reference']['teacher_board_id'];
                            $application->agreed_terms_ts = $v['agreedterms'];
                            $application->state = $v['state'];
                            $application->state_ts = $v['statets'];
                            $application->reference = Json::encode(['id' => $key_applicant_id, 'application_choices' => $v['application_choices']]);
                            $application->deleted = Application::APPLICATION_NOT_DELETED;
                            if (false === ($save = $application->save())) {
                                throw new \Exception(Yii::t('substituteteacher', 'Could not save teacher application.'));
                            }

                            $application_positions = array_filter($applications_parsed, function ($v) use ($key_applicant_id) {
                                return $v['applicant_id'] == $key_applicant_id;
                            });
                            if ($v['application_choices'] != count($application_positions)) {
                                throw new \Exception(Yii::t('substituteteacher', 'Teacher application information mismatch.'));
                            }
                            foreach ($application_positions as $application_position) {
                                $call_position_ids = $choices_parsed[$application_position['choice_id']]['reference']['id'];
                                if (!is_array($call_position_ids)) {
                                    $call_position_ids = [ $call_position_ids ]; // just to unify handling
                                }
                                foreach ($call_position_ids as $call_position_id) {
                                    $app_position = new ApplicationPosition;
                                    $app_position->application_id = $application->id;
                                    $app_position->call_position_id = $call_position_id;
                                    $app_position->order = $application_position['order'];
                                    $app_position->updated = $application_position['updated'];
                                    $app_position->deleted = $application_position['deleted'];
                                    if (false === ($save = $app_position->save())) {
                                        throw new \Exception(Yii::t('substituteteacher', 'Could not save teacher application position.'));
                                    }
                                }
                            }
                            // mark applicants that denied
                            // mark applicants that applied to be used in placement procedures
                        });

                        // LOG everything
                        $transaction->commit();
                        $status_data = true;
                    } catch (WrongKeyOrModifiedCiphertextException $ex) {
                        $transaction->rollBack();
                        $messages_data[] = Yii::t('substituteteacher', 'Data received contains data with invalid encoding.') .
                        ' (' . $ex->getMessage() . ')';
                    } catch (\Exception $ex) {
                        $transaction->rollBack();
                        $messages_data[] = Yii::t('substituteteacher', 'Invalid or malformed data or error while parsing data.') .
                        ' (' . $ex->getMessage() . ')';
                    }
                }
            }
        }

        return $this->render('receive', compact('call_model', 'connection_options', 'status_unload', 'status_data', 'message_unload', 'messages_data'));
    }

    /**
     * @throws NotFoundHttpException if the fetch data cannot be found
     */
    public function actionFetch($what)
    {
        switch ($what) {
            case 'teacher':
                $ids = array_filter(explode(',', ($tids = \Yii::$app->request->post('ids', ''))));
                $dataProvider = new ArrayDataProvider([
                    'allModels' => Teacher::find()
                        ->where(['id' => $ids])
                        ->all(),
                    'pagination' => false,
                ]);
                \Yii::info('Fetch teachers', __METHOD__);
                return $this->renderAjax('_teacher_list', compact('dataProvider'));
                break;
            default:
                \Yii::warning("Fetch unknown [{$what}]", __METHOD__);
                throw new NotFoundHttpException();
                break;
        }
    }

    /**
     * Choose a call and send relevant data to applications frontend.
     *
     * @param int|null call_id
     */
    public function actionSend($call_id = 0)
    {
        $connection_options = $this->options;

        $call_model = Call::findOne(['id' => $call_id]);
        // if call is selected, collect positions, prefectures, teachers and placement preferences
        if (!empty($call_model)) {
            // no filtering on prefectures; catalogue only
            $prefectures = Prefecture::find()->all();
            $prefecture_substitutions = [];
            $prefectures = array_map(function ($k) use ($prefectures, &$prefecture_substitutions) {
                $index = $k + 1;
                $prefecture_substitutions[$index] = $prefectures[$k]->id;
                return array_merge(['index' => $index], $prefectures[$k]->toApi());
            }, array_keys($prefectures));

            // collect the call positions of the specific call;
            // also get prefectures that will be used to filter teachers
            $call_positions_prefectures = [];
            $call_pos_pref_by_specialisation = [];
            $call_positions_school_types = [];
            $call_positions = CallPosition::findOnePerGroup($call_id);
            $call_positions = array_map(function ($k) use (&$call_positions_prefectures, $call_positions, $prefecture_substitutions, &$call_pos_pref_by_specialisation, &$call_positions_school_types) {
                $index = $k + 1;
                $spec_id = $call_positions[$k]->position->specialisation_id;
                if (!array_key_exists("{$spec_id}", $call_pos_pref_by_specialisation)) {
                    $call_pos_pref_by_specialisation["{$spec_id}"] = [];
                    $call_positions_school_types["{$spec_id}"] = [Position::SCHOOL_TYPE_DEFAULT => false, Position::SCHOOL_TYPE_KEDDY => false];
                }
                $call_pos_pref_by_specialisation["{$spec_id}"][] = $call_positions[$k]->position->prefecture_id;
                $call_positions_school_types["{$spec_id}"][$call_positions[$k]->position->school_type] = true;

                $call_positions_prefectures[] = $call_positions[$k]->position->prefecture_id;
                return array_merge(['index' => $index], $call_positions[$k]->toApi($prefecture_substitutions));
            }, array_keys($call_positions));
            $call_positions_prefectures = array_unique($call_positions_prefectures);

            // get the teachers that meet the following criteria:
            // - they belong to the relevant boards (year / specialisation)
            // - they are eligible for appointment
            // - they have priority for appointment (top X in board)
            // To avoid huge joins, get the list of applicable specialisations prior to selecting teachers
            $teachers = [];
            $teacherboard_table = TeacherBoard::tableName();
            $call_teacher_specialisations = $call_model->callTeacherSpecialisations;
            if (empty($call_teacher_specialisations)) {
                // no teachers wanted...
                $call_teacher_specialisations = [];
            }

            // keep track of specialisations and counts of teachers
            $teacher_counts = [];
            // Get the list per specialisation and combine all teachers
            foreach ($call_teacher_specialisations as $call_teacher_specialisation) {
                if (!array_key_exists("{$call_teacher_specialisation->specialisation_id}", $call_pos_pref_by_specialisation)) {
                    continue; // skip specialisations that do not apply in specific call positions
                }
                $school_types = array_merge([0], array_keys(array_filter($call_positions_school_types["{$call_teacher_specialisation->specialisation_id}"])));

                $extra_wanted = intval($call_teacher_specialisation->teachers * (1 + $this->module->params['extra-call-teachers-percent']));
                $call_specialisation_teachers_pool = Teacher::find()
                    ->year($call_model->year)
                    ->status(Teacher::TEACHER_STATUS_ELIGIBLE)
                    ->joinWith(['boards', 'registry', 'registry.specialisations', 'placementPreferences'])
                    ->andWhere(["{$teacherboard_table}.[[specialisation_id]]" => $call_teacher_specialisation->specialisation_id])
                    ->andWhere(["{$teacherboard_table}.[[specialisation_id]]" => new Expression(TeacherRegistrySpecialisation::tableName() . '.[[specialisation_id]]')])
                    // ->andWhere([PlacementPreference::tableName() . ".[[prefecture_id]]" => $call_positions_prefectures])
                    ->andWhere([
                        PlacementPreference::tableName() . ".[[prefecture_id]]" => $call_pos_pref_by_specialisation["{$call_teacher_specialisation->specialisation_id}"],
                        PlacementPreference::tableName() . ".[[school_type]]" => $school_types
                    ])
                    ->orderBy([
                        "{$teacherboard_table}.[[board_type]]" => SORT_ASC,
                        "{$teacherboard_table}.[[order]]" => SORT_ASC,
                        "{$teacherboard_table}.[[points]]" => SORT_ASC, // in case order is not used
                    ])
                    ->select([
                        Teacher::tableName() . ".[[id]]",
                        // "{$teacherboard_table}.[[specialisation_id]]"
                    ]);

                $call_specialisation_teachers = Teacher::find()
                    ->where(['id' => (new \yii\db\Query())
                        ->select(['id'])
                        ->distinct()
                        ->from(['au' => $call_specialisation_teachers_pool])
                    ])
                    ->limit($extra_wanted) // only get the number of teachers wanted
                    ->all();
                array_walk($call_specialisation_teachers, function ($model, $k) use ($call_teacher_specialisation) {
                    $model->setScenario(Teacher::SCENARIO_CALL_FETCH);
                    $model->call_use_specialisation_id = $call_teacher_specialisation->specialisation_id;
                    return;
                });

                // keep track of specialisations and counts of teachers
                $teacher_counts[] = [
                    'specialisation' => $call_teacher_specialisation->specialisation->label,
                    'wanted' => $call_teacher_specialisation->teachers,
                    'extra_wanted' => $extra_wanted,
                    'available' => count($call_specialisation_teachers)
                ];
                $teachers = array_merge($teachers, $call_specialisation_teachers);
            }
            $placement_preferences = [];
            $walk = array_walk($teachers, function ($m, $k) use (&$placement_preferences) {
                $placement_preferences = array_merge($placement_preferences, $m->placementPreferences);
            });
            $teacher_substitutions = [];
            $teacher_ids = array_map(function ($m) {
                return $m->id;
            }, $teachers);
            $teachers = array_map(function ($k) use ($teachers, &$teacher_substitutions) {
                $index = $k + 1;
                $teacher_substitutions[$index] = $teachers[$k]->id;
                return array_merge(['index' => $index], $teachers[$k]->toApi());
            }, array_keys($teachers));
            $placement_preferences = array_map(function ($k) use ($placement_preferences, $prefecture_substitutions, $teacher_substitutions) {
                $index = $k + 1;
                return array_merge(['index' => $index], $placement_preferences[$k]->toApi($prefecture_substitutions, $teacher_substitutions));
            }, array_keys($placement_preferences));

            // GET request displays "dry-run" results
            // POST does the actual sending of data
            $data = [
                'version' => '1.0',
                'prefectures' => $prefectures,
                'teachers' => $teachers,
                'positions' => $call_positions,
                'placement_preferences' => $placement_preferences
            ];
            $count_prefectures = count($prefectures);
            $count_teachers = count($teachers);
            $count_call_positions = count($call_positions);
            $count_placement_preferences = count($placement_preferences);

            $status_clear = null;
            $status_load = null;

            \Yii::info([
                "#prefectures = [$count_prefectures]",
                "#teachers = [$count_teachers]",
                "#call_positions = [$count_call_positions]",
                "#placement_preferences = [$count_placement_preferences]",
                $teacher_counts
            ], __METHOD__);

            if (\Yii::$app->request->isPost) {
                // first issue a clear command
                \Yii::info(['Call [clear] with [delete] method', $connection_options], __METHOD__);
                $status_response = $this->client->delete('clear', null, $this->getHeaders())->send();
                $status_clear = $status_response->isOk ? $status_response->isOk : $status_response->statusCode;
                $response_data_clear = $status_response->getData();
                if ($status_clear !== true) {
                    \Yii::error([$status_clear, $response_data_clear], __METHOD__);
                } else {
                    \Yii::info([$status_clear, $response_data_clear], __METHOD__);
                }
                if ($status_clear === true) {
                    // then post data
                    \Yii::info(['Call [load] with [post] method', $connection_options], __METHOD__);
                    $status_response = $this->client->post('load', $data, $this->getHeaders())->send();
                    $status_load = $status_response->isOk ? $status_response->isOk : $status_response->statusCode;
                    $response_data_load = $status_response->getData();
                    if ($status_load !== true) {
                        \Yii::error([$status_load, $response_data_load], __METHOD__);
                    } else {
                        \Yii::info([$status_load, $response_data_load], __METHOD__);
                    }
                }
            }
        }

        return $this->render('send', compact('call_model', 'teacher_ids', 'status_clear', 'response_data_clear', 'status_load', 'response_data_load', 'data', 'count_prefectures', 'count_teachers', 'teacher_counts', 'count_call_positions', 'count_placement_preferences', 'connection_options'));
    }
}
