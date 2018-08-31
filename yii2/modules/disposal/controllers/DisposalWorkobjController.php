<?php

namespace app\modules\disposal\controllers;

use Exception;
use Yii;
use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\DisposalWorkobj;
use app\modules\disposal\models\DisposalWorkobjSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DisposalWorkobjController implements the CRUD actions for DisposalWorkobj model.
 */
class DisposalWorkobjController extends Controller
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
     * Lists all DisposalWorkobj models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DisposalWorkobjSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DisposalWorkobj model.
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
     * Creates a new DisposalWorkobj model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    { 
        try {
            $model = new DisposalWorkobj();            
            if ($model->load(Yii::$app->request->post())) {
                if(!$model->save())
                    throw new Exception();
                
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
     * Updates an existing DisposalWorkobj model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        try {
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post())) {
                if(!$model->save())
                    throw new Exception();
                                
                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The details of the disposal duty were saved successfully."));
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
     * Deletes an existing DisposalWorkobj model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            if(!$this->findModel($id)->delete())
                throw new Exception();
            
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The disposal duty was deleted successfully."));
            return $this->redirect(['index']);
        }
        catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The deletion of the disposal duty failed."));
            return $this->redirect(['index']);
        }
        
    }

    /**
     * Finds the DisposalWorkobj model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DisposalWorkobj the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DisposalWorkobj::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
