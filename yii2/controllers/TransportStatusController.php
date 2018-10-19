<?php

namespace app\controllers;

use Yii;
use app\models\TransportStatus;
use app\models\TransportStatusSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TransportStatusController implements the CRUD actions for TransportStatus model.
 */
class TransportStatusController extends Controller
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
                    // [
                    //     'actions' => ['index', 'view'],
                    //     'allow' => true,
                    //     'roles' => ['@'],
                    // ],
                    // [
                    //     'actions' => ['create', 'update'],
                    //     'allow' => true,
                    //     'roles' => ['admin', 'user', 'transport_user'],
                    // ],
                    [
                        'actions' => ['index', 'view', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['admin', 'transport_user'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all TransportStatus models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransportStatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransportStatus model.
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
     * Creates a new TransportStatus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TransportStatus();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $userName = Yii::$app->user->identity->username;
            $logStr = 'User ' . $userName . ' created transport status with id [' . $model->id . ']';
            Yii::info($logStr, 'transport');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TransportStatus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $userName = Yii::$app->user->identity->username;
            $logStr = 'User ' . $userName . ' updated transport status with id [' . $model->id . ']';
            Yii::info($logStr, 'transport');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TransportStatus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $userName = Yii::$app->user->identity->username;
        $logStr = 'User ' . $userName . ' deleted transport status with id [' . $id . ']';
        Yii::info($logStr, 'transport');
        return $this->redirect(['index']);
    }

    /**
     * Finds the TransportStatus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TransportStatus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransportStatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
