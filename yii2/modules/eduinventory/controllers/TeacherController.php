<?php

namespace app\modules\eduinventory\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\base\models\FileImport;
use app\modules\eduinventory\models\Teacher;
use app\modules\eduinventory\models\TeacherSearch;
use app\modules\schooltransport\models\Schoolunit;
use app\models\Specialisation;
use app\modules\eduinventory\EducationInventoryModule;

/**
 * TeacherController implements the CRUD actions for Teacher model.
 */
class TeacherController extends Controller
{
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
            'access' => [   'class' => AccessControl::className(),
                'rules' =>  [
                    ['actions' => ['index', 'view'], 'allow' => true, 'roles' => ['eduinventory_viewer']],
                    ['actions' => ['create', 'update'], 'allow' => true, 'roles' => ['eduinventory_editor']],
                    ['actions' => ['import', 'delete'], 'allow' => true, 'roles' => ['eduinventory_director']]
                ]
            ]
        ];
    }

    /**
     * Lists all Teacher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Teacher model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $teacher_model = $this->findModel($id);
        $school = Schoolunit::findOne(['school_id' => $teacher_model->school_id]);
        $teacher_model['school_id'] = $school['school_name'];
        return $this->render('view', [
            'model' => $teacher_model,
            'school' => $school,
        ]);
    }

    /**
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        try {
            $model = new Teacher();
            $schools = Schoolunit::find()->all();
            $specialisations = Specialisation::find()->all();

            if ($model->load(Yii::$app->request->post())) {
                if (!$model->save()) {
                    throw new Exception("Error saving teacher details in the database.");
                }

                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'created Teacher with id "' . $model->teacher_id. '".', 'eduinventory');

                Yii::$app->session->addFlash('success', Yii::t('app', "The teacher was created successfully."));
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'schools' => $schools,
                    'specialisations' => $specialisations
                ]);
            }
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', Yii::t('app', $exc->getMessage()));
            return $this->render('create', [
                'model' => $model,
                'schools' => $schools,
                'specialisations' => $specialisations
            ]);
        }
    }

    /**
     * Updates an existing Teacher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        try {
            $model = $this->findModel($id);
            $schools = Schoolunit::find()->all();
            $specialisations = Specialisation::find()->all();

            if ($model->load(Yii::$app->request->post())) {
                if (!$model->save()) {
                    throw new Exception("Error saving teacher in the database.");
                }

                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'updated Teacher with id "' . $model->teacher_id. '".', 'eduinventory');
                Yii::$app->session->addFlash('success', Yii::t('app', "The teacher details were updated successfully."));
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'schools' => $schools,
                    'specialisations' => $specialisations
                ]);
            }
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', Yii::t('app', $exc->getMessage()));
            return $this->render('update', [
                'model' => $model,
                'schools' => $schools,
                'specialisations' => $specialisations
            ]);
        }
    }

    /**
     * Deletes an existing Teacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            if (!$this->findModel($id)->delete()) {
                throw new Exception("Error: trying to delete an non-existing teacher.");
            }

            $user = Yii::$app->user->identity->username;
            Yii::info('User ' . $user . ' ' . 'deleted Teacher with id "' . $id. '".', 'eduinventory');
            Yii::$app->session->addFlash('success', Yii::t('app', "The teacher was deleted successfully."));
            return $this->redirect(['index']);
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', Yii::t('app', $exc->getMessage()));
            return $this->redirect(['index']);
        }
    }


    public function actionImport()
    {
        /*
         *  From param file:
         *  'teachersimport_excelfile_columns' => [  'AM' => 1, 'AFM' => 2, 'GENDER' => '3', 'SURNAME' => 4, 'NAME' => 5, 'FATHERNAME' => 6, 'MOTHERNAME' => 7, 'SPECIALISATION' => 14, 'ORGANIC_SCHOOL' => 35]
         */

        $teachers_columns = $this->module->params['teachersimport_excelfile_columns'];

        $base_teachersdata_row = 2;
        try {
            $transaction = Yii::$app->db->beginTransaction();
            $import_model = new FileImport();

            if ($import_model->load(Yii::$app->request->post())) {
                $import_model->upload($this->module->params['eduinventory_importfolder']);

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(Yii::getAlias(Yii::$app->controller->module->params['eduinventory_importfolder']) . $import_model->importfile);
                if (!$spreadsheet) {
                    throw new Exception("Error in opening Excel file.");
                }

                $teachers_worksheet = $spreadsheet->getSheet(0);
                $rowiterator = $teachers_worksheet->getRowIterator($base_teachersdata_row, null);

                foreach ($rowiterator as $row) {
                    $currentrow_index = $row->getRowIndex();

                    $currentteacher_am = trim($teachers_worksheet->getCellByColumnAndRow($teachers_columns['AM'], $currentrow_index)->getFormattedValue());
                    $currentteacher_afm = trim($teachers_worksheet->getCellByColumnAndRow($teachers_columns['AFM'], $currentrow_index)->getFormattedValue());
                    if (!strcmp($currentteacher_am, "") && !strcmp($currentteacher_afm, "")) {
                        break;
                    }

                    $teacher_model = null;
                    if (strcmp($currentteacher_am, "")) {
                        $teacher_model = Teacher::find()->where(['teacher_registrynumber' => $currentteacher_am])->one();
                    } elseif (strcmp($currentteacher_afm, "")) {
                        $teacher_model = Teacher::find()->where(['teacher_afm' => $currentteacher_afm])->one();
                    } else {
                        throw new Exception("@teacher id read");
                    }

                    if (!$teacher_model) {
                        $teacher_model = new Teacher();
                    }
                    $teacher_model->teacher_registrynumber = ($currentteacher_am == "") ? null : $currentteacher_am;
                    $teacher_model->teacher_afm = ($currentteacher_afm == "") ? null : $currentteacher_afm;
                    $teacher_model->teacher_surname = $teachers_worksheet->getCellByColumnAndRow($teachers_columns['SURNAME'], $currentrow_index)->getValue();
                    $teacher_model->teacher_name = $teachers_worksheet->getCellByColumnAndRow($teachers_columns['NAME'], $currentrow_index)->getValue();
                    $gender = $teachers_worksheet->getCellByColumnAndRow($teachers_columns['GENDER'], $currentrow_index)->getValue();
                    if (!strcasecmp($gender, 'Θ')) {
                        $teacher_model->teacher_gender = Teacher::FEMALE;
                    } elseif (!strcasecmp($gender, 'A') || !strcasecmp($gender, 'Α')) {
                        $teacher_model->teacher_gender = Teacher::MALE;
                    }
                    $teacher_model->teacher_fathername = $teachers_worksheet->getCellByColumnAndRow($teachers_columns['FATHERNAME'], $currentrow_index)->getValue();
                    $teacher_model->teacher_mothername = $teachers_worksheet->getCellByColumnAndRow($teachers_columns['MOTHERNAME'], $currentrow_index)->getValue();
                    $teacher_model->school_id = Schoolunit::findOne(['school_mineducode' => intval($teachers_worksheet->getCellByColumnAndRow($teachers_columns['ORGANIC_SCHOOL'], $currentrow_index)->getFormattedValue())])['school_id'];
                    $specialisation = $teachers_worksheet->getCellByColumnAndRow($teachers_columns['SPECIALISATION'], $currentrow_index)->getValue();
                    $specialisation = $this->temporaryModification($specialisation);
                    $specialisation_with_blank = mb_substr($specialisation, 0, 2) . ' ' . mb_substr($specialisation, 2, null, 'UTF-8');
                    $specialisation_id = Specialisation::find()->where(['code' => $specialisation])->orWhere(['code' => $specialisation_with_blank])->one()['id'];
                    $teacher_model->specialisation_id = $specialisation_id;
                    if (!$teacher_model->save()) {
                        //echo "<pre>"; print_r($teacher_model); echo "</pre>"; die();
                        throw new Exception("(@teacher_save)");
                    }
                }

                $transaction->commit();

                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'imported teachers from Excel file.', 'eduinventory');

                Yii::$app->session->addFlash('success', EducationInventoryModule::t('modules/eduinventory/app', "The teachers were imported successfully."));
                return $this->redirect(['index']);
            } else {
                return $this->render('import', [
                    'import_model' => $import_model,
                ]);
            }
        } catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', EducationInventoryModule::t('modules/eduinventory/app', "Error in importing teachers. " . $exc->getMessage()));
            return $this->redirect(['index']);
        }
    }

    private function temporaryModification($specialisation)
    { /* TODO REMOVE WHEN SubstituteTeacher Front will be corrected */
        if ($specialisation == "ΔΕ1ΕΒΠ") {
            return 'ΔΕ1';
        } elseif ($specialisation == "ΠΕ21") {
            return "ΠΕ 2100";
        } elseif ($specialisation == "ΠΕ22") {
            return "ΠΕ 2200";
        } elseif ($specialisation == "ΠΕ23") {
            return "ΠΕ 2300";
        } elseif ($specialisation == "ΠΕ24") {
            return "ΠΕ 2400";
        } elseif ($specialisation == "ΠΕ25") {
            return "ΠΕ 2500";
        } elseif ($specialisation == "ΠΕ28") {
            return "ΠΕ 2800";
        } elseif ($specialisation == "ΠΕ29") {
            return "ΠΕ 2900";
        } elseif ($specialisation == "ΠΕ30") {
            return "ΠΕ 3000";
        } elseif ($specialisation == "ΠΕ31") {
            return "ΠΕ 3100";
        } elseif ($specialisation == "ΧΟΡΟΣ-ΚΛΑΣΣΙΚΟΣ") {
            return "ΧΟΡΟΣ-ΚΛΑΣ";
        } elseif ($specialisation == "ΘΚ-ΣΚΗΝΟΘΕΤΗΣ") {
            return "ΘΚ-ΣΚΗΝΟΘ";
        } elseif ($specialisation == "ΧΟΡΟΣ-ΣΥΓΧΡΟΝΟΣ") {
            return "ΧΟΡΟΣ-ΣΥΓΧ";
        } else {
            return $specialisation;
        }
    }


    private function writeLog($infomessage)
    {
        $user = Yii::$app->user->identity->username;
        Yii::info('User ' . $user . ' ' . $infomessage, 'eduinventory');
    }

    /**
     * Finds the Teacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Teacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Teacher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
