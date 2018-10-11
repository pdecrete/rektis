<?php

namespace app\controllers;

use Yii;
use app\models\Teacher;
use app\models\TeacherSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use app\modules\schooltransport\models\Schoolunit;
use app\models\Specialisation;

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
                if(!$model->save()) 
                    throw new Exception("Error saving teacher details in the database.");
                    
                Yii::$app->session->addFlash('success', Yii::t('app', "The teacher was created successfully."));
                return $this->redirect(['index']);
            } 
            else {
                return $this->render('create', [
                    'model' => $model,
                    'schools' => $schools,
                    'specialisations' => $specialisations
                ]);
            }
        }
        catch (Exception $exc) {
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
                if(!$model->save())
                    throw new Exception("Error saving teacher in the database.");
                    
                    Yii::$app->session->addFlash('success', Yii::t('app', "The teacher details were updated successfully."));
                    return $this->redirect(['index']);
            }
            else {
                return $this->render('update', [
                    'model' => $model,
                    'schools' => $schools,
                    'specialisations' => $specialisations
                ]);
            }
        }
        catch (Exception $exc) {
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
            if(!$this->findModel($id)->delete())
                throw new Exception("Error: trying to delete an non-existing teacher.");
                        
            Yii::$app->session->addFlash('success', Yii::t('app', "The teacher was deleted successfully."));
            return $this->redirect(['index']);
        }
        catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', Yii::t('app', $exc->getMessage()));
            return $this->redirect(['index']);
        }
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
