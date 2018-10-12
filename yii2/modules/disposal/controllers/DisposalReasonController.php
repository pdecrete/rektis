<?php

namespace app\modules\disposal\controllers;

use Exception;
use Yii;
use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\DisposalReason;
use app\modules\disposal\models\DisposalReasonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DisposalReasonController implements the CRUD actions for DisposalReason model.
 */
class DisposalReasonController extends Controller
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
        ];
    }

    /**
     * Lists all DisposalReason models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DisposalReasonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DisposalReason model.
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
     * Creates a new DisposalReason model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {       
        try {
            $model = new DisposalReason();
            
            if($model->load(Yii::$app->request->post())){
                if(!$model->save())
                    throw new Exception("Error in saving the details of the disposal reason in the database");
                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The details of the disposal reason were saved successfully."));
                return $this->redirect(['index']);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DisposalReason model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {        
        try {
            $model = $this->findModel($id);
            
            if($model->load(Yii::$app->request->post())){
                if(!$model->save())
                    throw new Exception();
                    Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The details of the disposal reason were saved successfully."));
                    return $this->redirect(['index']);
            }
            else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DisposalReason model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            if(!$this->findModel($id)->delete())
                throw new Exception("The disposal reason cannot be deleted.");
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The disposal reason was deleted successfully."));
            return $this->redirect(['index']);
        }
        catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->redirect(['index']);
        }           
    }

    /**
     * Finds the DisposalReason model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DisposalReason the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DisposalReason::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
