<?php

namespace app\controllers;

use Yii;
use app\models\Leave;
use app\models\LeavePrint;
use app\models\LeaveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\VerbFilter;
use \PhpOffice\PhpWord\TemplateProcessor;
use yii\filters\AccessControl;

/**
 * LeaveController implements the CRUD actions for Leave model.
 */
class LeaveController extends Controller
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
//                    'print' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'user'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Leave models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LeaveSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 
     * @param Leave $leaveModel
     * @return String the generated file filename
     * @throws NotFoundHttpException
     */
    protected function generatePrintDocument($leaveModel)
    {
        $dts = date('YmdHis');
        $filename = Yii::getAlias("@vendor/admapp/exports/ADEIA_TEST_FILE_{$dts}.docx");

        // TODO replace ADEIA_TEST_FILE.docx with file from LeaveTypes 
        $templateProcessor = new TemplateProcessor(Yii::getAlias('@vendor/admapp/resources/ADEIA_TEST_FILE.docx'));
        $templateProcessor->setValue('DATE', date('d/m/Y'));
        $templateProcessor->setValue('PROTOCOL', $leaveModel->decision_protocol);
        $templateProcessor->setValue('FULLNAME', $leaveModel->employeeObj->fullname);
        $templateProcessor->saveAs($filename);
        if (!is_readable($filename)) {
            throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested leave was not generated.'));
        }

        return $filename;
    }

    protected function setPrintDocument($leaveModel, $filename)
    {
//        LeavePrint::deleteAll(['leave' => $model->id]);
        foreach ($leaveModel->leavePrints as $print) {
            $unlink_filename = $print->path;
            if (file_exists($unlink_filename)) {
                unlink($unlink_filename);
            }
            $print->delete();
        }
        $new_print = new LeavePrint();
        $new_print->filename = basename($filename);
        $new_print->leave = $leaveModel->id;
        $ins = $new_print->insert();

        return $ins;
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested leave is deleted.'));
        }

        if (($prints = $model->leavePrints) != null) {
            $filename = $prints[0]->filename;
        } else {
            // generate document if it does not exist
            $filename = $this->generatePrintDocument($model);
            Yii::$app->session->addFlash('success', Yii::t('app', 'Succesfully generated file on {date}.', ['date' => date('d/m/Y')]));
            $set = $this->setPrintDocument($model, $filename);
        }

        // if file is STILL not generated, redirect to page
        if (!is_readable(LeavePrint::path($filename))) {
            return $this->redirect(['print', 'id' => $model->id]);
        }

        // all well, send file 
        Yii::$app->response->sendFile(LeavePrint::path($filename));
    }

    /**
     * Locate a Leave and generate / download a document for it. 
     * If a document is not already generated, it is generated. 
     * A link to download the document is provided in the view. 
     * 
     * @param integer $id
     * @throws NotFoundHttpException
     */
    public function actionPrint($id)
    {
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested leave is deleted.'));
        }

        $filename = $this->generatePrintDocument($model);
        $set = $this->setPrintDocument($model, $filename);

        return $this->render('print', [
                    'model' => $model,
                    'filename' => $filename
        ]);
    }

    /**
     * Displays a single Leave model.
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
     * Creates a new Leave model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Leave();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Leave model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes (marks as deleted) an existing Leave model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ServerErrorHttpException if the model cannot be deleted
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
        $model = $this->findModel($id);
        $model->deleted = 1;
        if ($model->save()) {
            return $this->redirect(['index']);
        } else {
            throw new ServerErrorHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Leave model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Leave the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Leave::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
