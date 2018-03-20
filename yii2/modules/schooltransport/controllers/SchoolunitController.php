<?php

namespace app\modules\schooltransport\controllers;

use Yii;
use app\modules\schooltransport\Module;
use app\modules\schooltransport\models\Schoolunit;
use app\modules\schooltransport\models\SchoolunitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use app\modules\schooltransport\models\Directorate;

/**
 * SchoolunitController implements the CRUD actions for Schoolunit model.
 */
class SchoolunitController extends Controller
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
     * Lists all Schoolunit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SchoolunitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Schoolunit model.
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
     * Creates a new Schoolunit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Schoolunit();
        $directorates = Directorate::find()->all();
        
        try{                
            if ($model->load(Yii::$app->request->post())){
                if(!$model->save())
                    throw new Exception("Failure in creating school unit.");
                Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The school unit was created successfully"));
                return $this->redirect(['index']);
            }
            else {
                return $this->render('create', ['model' => $model, 'directorates' => $directorates]);
            }
        }
        catch (Exception $exc){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('create', ['model' => $model, 'directorates' => $directorates]);            
        }
    }

    /**
     * Updates an existing Schoolunit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $directorates = Directorate::find()->all();
        
        try{
            if ($model->load(Yii::$app->request->post())){
                
                if(!$model->save()) {
                    throw new Exception("Failure in updating school unit");
                }
                Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The school unit was updated successfully"));
                return $this->redirect(['index']);
            }
            else {
                return $this->render('update', ['model' => $model, 'directorates' => $directorates]);
            }
        }
        catch(Exception $exc){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('create', ['model' => $model, 'directorates' => $directorates]);            
        }
    }

    /**
     * Deletes an existing Schoolunit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try{
            if(!$this->findModel($id)->delete())
                throw new Exception("Failure in deleting school unit");
            Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The school unit was deleted successfully"));
            return $this->redirect(['index']);
        }
        catch (Exception $exc){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('index');            
        }
        
    }

    /**
     * Finds the Schoolunit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Schoolunit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Schoolunit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
