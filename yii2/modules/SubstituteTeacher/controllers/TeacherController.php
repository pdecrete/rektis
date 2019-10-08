<?php

namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use kartik\mpdf\Pdf;
use \PhpOffice\PhpWord\TemplateProcessor;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\TeacherSearch;
use app\modules\SubstituteTeacher\models\TeacherSearchMK;
use app\modules\SubstituteTeacher\models\Operation;
use app\modules\SubstituteTeacher\models\PlacementPreference;
use app\modules\SubstituteTeacher\models\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UnprocessableEntityHttpException;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\modules\SubstituteTeacher\models\TeacherBoard;

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
                    'appoint' => ['POST'],
                    'negate' => ['POST'],
                    'eligible' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'import',],
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
     * Lists all Teacher models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember('', 'teacherindex');

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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Teacher();
        $modelsPlacementPreferences = [new PlacementPreference];

        if ($model->load(Yii::$app->request->post())) {
            $post = \Yii::$app->request->post();

            if (isset($post['PlacementPreference'])) {
                $post['PlacementPreference'] = array_values($post['PlacementPreference']);
            }
            $modelsPlacementPreferences = Model::createMultiple(PlacementPreference::classname(), $modelsPlacementPreferences);
            Model::loadMultiple($modelsPlacementPreferences, Yii::$app->request->post());

            // validate all models
            $valid = $model->validate();
            $audit_info = [
                'registry_id' => $model->registry_id, 
                'year' => $model->year
            ];

            $valid = PlacementPreference::checkOrdering($modelsPlacementPreferences) && $valid;
            $valid = PlacementPreference::checkRules($modelsPlacementPreferences) && $valid;
            //Teacher::calcMK($model);
            
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    //echo $model->mk;die();
                    if ($flag = $model->save(false)) { // already validated
                        $audit_info['TeacherBoardSaved'] = 0;

                        $id = $model->id;
                        array_walk($modelsPlacementPreferences, function ($m) use ($id) {
                            $m->setScenario(PlacementPreference::SCENARIO_MASS_UPDATE);
                            $m->teacher_id = $id;
                        });

                        // $valid = Model::validateMultiple($modelsPlacementPreferences) && $valid;

                        foreach ($modelsPlacementPreferences as $modelPlacementPreference) {
                            if (! ($flag = $modelPlacementPreference->save())) {
                                $transaction->rollBack();
                                break;
                            }
                            $audit_info['TeacherBoardSaved']++;
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Ολοκληρώθηκε με επιτυχία η εισαγωγή των στοιχείων.');
                        $model->audit('Καταχώρηση στοιχείων αναπληρωτή', $audit_info);
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η εισαγωγή των στοιχείων λόγω τεχνικού προβλήματος.');
                    Yii::$app->session->addFlash('danger', $e->getMessage());
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
            'modelsPlacementPreferences' => $modelsPlacementPreferences ? $modelsPlacementPreferences : [ new PlacementPreference]
        ]);
    }

    protected function getModelsBoards($model)
    {
        $id = $model->id;

        $specialisations = array_map(function ($m) {
            return $m->id;
        }, $model->registry->specialisations);
        $specialisations_boards = array_map(function ($m) {
            return $m->specialisation_id;
        }, $model->boards);
        $missing_specialisations = array_diff($specialisations, $specialisations_boards);

        $modelsBoards = $model->boards;
        if (!empty($missing_specialisations)) {
            $modelsBoards = array_merge($modelsBoards, array_map(function ($spec_id) use ($id) {
                $new_entry = new TeacherBoard;
                $new_entry->id = - $spec_id;
                $new_entry->teacher_id = $id;
                $new_entry->specialisation_id = $spec_id;
                return $new_entry;
            }, $missing_specialisations));
        }

        return $modelsBoards;
    }
    /**
     * Updates an existing Teacher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsPlacementPreferences = ($model->placementPreferences ? $model->placementPreferences : [new PlacementPreference]);

        $modelsBoards = $this->getModelsBoards($model);

        if ($model->load(Yii::$app->request->post())) {
            $post = \Yii::$app->request->post();
          
            $modelsBoards = Model::createMultiple(TeacherBoard::classname(), $modelsBoards);
            // need to feed the teacher id
            array_walk($modelsBoards, function (&$m, $k) use ($id) {
                if ($m->id == null) {
                    $m->teacher_id = $id;
                }
            });
            Model::loadMultiple($modelsBoards, $post);

            if (isset($post['PlacementPreference'])) {
                $post['PlacementPreference'] = array_values($post['PlacementPreference']);
            }
            $oldIDs = ArrayHelper::map($modelsPlacementPreferences, 'id', 'id');
            $modelsPlacementPreferences = Model::createMultiple(PlacementPreference::classname(), $modelsPlacementPreferences);
            Model::loadMultiple($modelsPlacementPreferences, $post);
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsPlacementPreferences, 'id', 'id')));

            array_walk($modelsPlacementPreferences, function ($m) use ($id) {
                $m->teacher_id = $id;
                $m->setScenario(PlacementPreference::SCENARIO_MASS_UPDATE);
            });

            // validate all models
            //echo $model->mk;die();
            $valid = $model->validate();
            $changed = $model->getDirtyAttributes();
            $valid = Model::validateMultiple($modelsPlacementPreferences) && $valid;

            $valid = PlacementPreference::checkOrdering($modelsPlacementPreferences) && $valid;
            $valid = PlacementPreference::checkRules($modelsPlacementPreferences) && $valid;
            //Teacher::calcMK($model);
            
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                   //echo $model->mk;die();
                    if ($flag = $model->save(false)) { // already validated
                        $changed['TeacherBoardDeleted'] = 0;
                        $changed['TeacherBoardSaved'] = 0;
                        foreach ($modelsBoards as $modelBoard) {
                            // those with empty values are considered not existant in the board
                            if (empty($modelBoard->board_type)
                                && empty($modelBoard->points)
                                && empty($modelBoard->order)) {
                                // remove if already existed or else ignore and skip it
                                if (!empty($modelBoard->id) && $modelBoard->id > 0) {
                                    $changed['TeacherBoardDeleted']++;
                                    $modelBoard->delete();
                                }
                                continue;
                            }

                            if (! ($flag = $modelBoard->save())) {
                                $transaction->rollBack();
                                break;
                            }
                            $changed['TeacherBoardSaved']++;
                        }

                        if ($flag) {
                            $changed['PlacementPreferenceDeleted'] = 0;
                            $changed['PlacementPreferenceSaved'] = 0;
                            if (! empty($deletedIDs)) {
                                $changed['PlacementPreferenceDeleted'] = PlacementPreference::deleteAll(['id' => $deletedIDs]);
                            }
                            foreach ($modelsPlacementPreferences as $modelPlacementPreference) {
                                if (! ($flag = $modelPlacementPreference->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                                $changed['PlacementPreferenceSaved']++;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Ολοκληρώθηκε με επιτυχία η ενημέρωση των στοιχείων.');
                        $model->audit('Ενημέρωση στοιχείων αναπληρωτή', $changed);
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η ενημέρωση των στοιχείων λόγω τεχνικού προβλήματος.');
                    Yii::$app->session->addFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsPlacementPreferences' => $modelsPlacementPreferences ? $modelsPlacementPreferences : [ new PlacementPreference],
            'modelsBoards' => $modelsBoards ? $modelsBoards : [ new TeacherBoard ],
        ]);
    }

    
    
    public function actionMkchange()
    {
 //        Url::remember('', 'teacherindex');

        $searchModel = new TeacherSearchMK();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->pagination->pagesize = 651;
        $dataProvider->setPagination(FALSE);
    
        $session = Yii::$app->session;
        // check if a session is already open
        if (!$session->isActive){
            $session->open();// open a session
        } 
        // save query here
        $session['repquery'] = Yii::$app->request->queryParams;

        
        
        return $this->render('mkchange', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    

    public function actionMkchangedecision()          
    {       
        $searchModel = new TeacherSearchMK();
        $dataProvider = $searchModel->search(Yii::$app->session->get('repquery'));      
 
        $dataProvider->setPagination(FALSE);

        $mkteachers = $dataProvider->getModels();

        $selteachers = json_decode(stripslashes($_POST['data']));
        $mode = json_decode(stripslashes($_POST['mode'])); 
        $pn = json_decode(stripslashes($_POST['pn']));
        $pd = json_decode(stripslashes($_POST['pd']));    
        $kat = json_decode(stripslashes($_POST['kat']));    
        //\Yii::$app->getSession()->setFlash($pd);
        
        $mkteacherarr = [];
        
        //$mkteachers = Teacher::findBySql()->where('mk_changedate >= :cdate', [':cdate' => $dt])->all();

        $i = 0;
        //$selteachers = explode(',',$selteacherstr);
        foreach ($mkteachers as $mkteacher) {
//            if (empty($mkteacher->sector)) {
//                continue;
//            } else {
            //if ($mkteacher->id==281) { echo "<pre>"; print_r($mkteacher); echo "</pre>";die(); } 
                if (in_array($mkteacher->id, $selteachers)) {
                    $registry_model = TeacherRegistry::findOne(['id' => $mkteacher->registry_id]);
                    $mkteacherarr[$i]['id'] = $mkteacher->registry_id;
                    $mkteacherarr[$i]['fullname'] = $registry_model->surname." ".$registry_model->firstname;
                    $mkteacherarr[$i]['fathername'] = $registry_model->fathername;
                    $mkteacherarr[$i]['mothername'] = $registry_model->mothername;
                    $mkteacherarr[$i]['specialty'] = $registry_model->specialisation_labels;
                    $mkteacherarr[$i]['mk'] = $mkteacher->mk;
                    $mkteacherarr[$i]['mk_appdate'] = $mkteacher->mk_appdate;
                    $mkteacherarr[$i]['mk_titleappdate'] = ($mkteacher->mk_titleyears > 0)? "NAI<br>".$mkteacher->mk_titleappdate : "";
                    $mkteacherarr[$i]['mk_expstr'] = $mkteacher->mk_years."Ε ".$mkteacher->mk_months."Μ ".$mkteacher->mk_days."ΗΜ";

                    $yp = ($mkteacher->mk_yearsper==2)?'-2 year':'-3 year';
    //                if ($mkteacher->mk_changedate !== null) {
                    if ($kat=="false") { 
                        if ($mkteacher->mk_changedate!== null) {
                            $mkteacherarr[$i]['mk_changedate'] = strtotime($yp, strtotime($mkteacher->mk_changedate));
                            $mkteacherarr[$i]['mk_changedate'] = date('Y-m-j', $mkteacherarr[$i]['mk_changedate']);
                        }
                        else {
                            $mkteacherarr[$i]['mk_changedate'] = '---';
                        }
                    } else {
                        if ($mkteacher->mk_appdate!== null) {
                            $mkteacherarr[$i]['mk_changedate'] = $mkteacher->mk_appdate;
                        } else {
                            $mkteacherarr[$i]['mk_changedate'] = '---';
                        }
                    }

                    $mkteacherarr[$i]['sector'] = $mkteacher->sectorlabel;
                    $mkteacherarr[$i]['operation'] = $mkteacher->operation_descr;

                    ++$i;
                }
//            }
        }
        //ArrayHelper::multisort($mkteacherarr, ['sector'], [SORT_ASC]); 
        if ($mode == "pdf") {
            $content = $this->renderPartial('mkchangedecision', [
                'mkteacherarr' => $mkteacherarr, 'mode' => $mode, 'pd' => $pd, 'pn' => $pn, 'kat' => $kat
            ]);            
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'filename' => 'mkchanging.pdf',
                'destination' => Pdf::DEST_DOWNLOAD,
                'content' => $content,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'options' => ['title' => 'Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης'],
            ]);
            return $pdf->render();
        } 
//        else if (mode == "word") {
//            //$dts = date('YmdHis');
//            $templatefilename = 'APODOSH_MISTHOLOGIKWN_KLIMAKIWN.docx';
//            $exportfilename = Yii::getAlias("@vendor/admapp/exports/{$templatefilename}");
//            
//            $templateProcessor = new TemplateProcessor(Yii::getAlias("@vendor/admapp/resources/{$templatefilename}"));
//            $templateProcessor->setValue('DECISION_DATE', Yii::$app->formatter->asDate($pd));
//            $templateProcessor->setValue('DECISION_PROTOCOL', $pn);
//
//            $templateProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['director_sign']);
//            $templateProcessor->setValue('DIRECTOR', Yii::$app->params['director']);            
//
//
//            $all_count = count($mkteachers);
//            $templateProcessor->cloneRow('FULLNAME', $all_count);
//            for ($c = 0; $c < $all_count; $c++) {
//                $i = $c + 1;
//                $currentModel = $sameDecisionModels[$c];
//                $templateProcessor->setValue('FULLNAME' . "#{$i}", $mkteacherarr[$i]['fullname']);
//                $templateProcessor->setValue('NAME' . "#{$i}", $currentModel->employeeObj->name);
//                $templateProcessor->setValue('FATHERNAME' . "#{$i}", $mkteacherarr[$i]['fathername']);
//                $templateProcessor->setValue('MOTHERNAME' . "#{$i}", $mkteacherarr[$i]['mothername']);
//                $templateProcessor->setValue('PRAXH' . "#{$i}", "---");
//                $templateProcessor->setValue('KLADOS' . "#{$i}", $mkteacherarr[$i]['specialty']);
//                $templateProcessor->setValue('DATE' . "#{$i}", $mkteacherarr[$i]['mk_appdate']);
//            }
//            
//            $templateProcessor->saveAs($exportfilename);
//            if (file_exists($exportfilename)) {
//                return \Yii::$app->response->sendFile(Yii::getAlias("@vendor/admapp/exports/"), $templatefilename, ['inline' => false])->send();
//            }
//          } 
    }    
    
    public function actionImport()
    {
        throw new \Exception('Not implemented yet');
    }

    /**
     * Deletes an existing Teacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws UnprocessableEntityHttpException If the teacher is involved in any process
     */
    public function actionDelete($id)
    {
        // throw new UnprocessableEntityHttpException(Yii::t('substituteteacher', 'The teacher cannot be deleted.'));        
        throw new \Exception('Not implemented yet');
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
