<?php

namespace app\controllers;

use Yii;
use app\models\TransportPrint;
use app\models\TransportPrintSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TransportPrintController implements the CRUD actions for TransportPrint model.
 */
class TransportPrintController extends Controller
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
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index', 'view'],
						'allow' => true,
						'roles' => ['@'],
					],
					[
						'allow' => true,
						'roles' => ['admin', 'user', 'transport_user'],
					],
				],
			],                                   
        ];
    }

    /**
     * Lists all TransportPrint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransportPrintSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransportPrint model.
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
     * Creates a new TransportPrint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TransportPrint();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TransportPrint model.
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
     * Deletes an existing TransportPrint model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TransportPrint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TransportPrint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransportPrint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
   	public function actionDownload($id)
    {
        $model = $this->findModel($id);
        if ($model != null) {
			$filename = $model->filename;
        } else { // print doesnot exist...
			throw new NotFoundHttpException(Yii::t('app', 'The requested transport file was not found.'));
        }
		if (is_readable(TransportPrint::path($filename))) {
			// all well, send file 
			Yii::$app->response->sendFile(TransportPrint::path($filename));
		} else {
			throw new NotFoundHttpException(Yii::t('app', 'The requested transport file was not found.'));	
		}
    }    

}
