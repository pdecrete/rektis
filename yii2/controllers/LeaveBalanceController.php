<?php

namespace app\controllers;

use Yii;
use app\models\LeaveBalance;
use app\models\Leave;
use app\models\LeaveType;
use app\models\LeaveBalanceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * LeaveBalanceController implements the CRUD actions for LeaveBalance model.
 */
class LeaveBalanceController extends Controller
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
                        'roles' => ['admin', 'user', 'leave_user'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all LeaveBalance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LeaveBalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LeaveBalance model.
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
     * Creates a new LeaveBalance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LeaveBalance();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $userName = Yii::$app->user->identity->username;
            $logStr = 'User ' . $userName . ' created leave_balance with id [' . $model->id . ']';
            Yii::info($logStr, 'leave');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->year = date("Y") - 1; //last year
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LeaveBalance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $userName = Yii::$app->user->identity->username;
            $logStr = 'User ' . $userName . ' updated leave_balance with id [' . $model->id . ']';
            Yii::info($logStr, 'leave');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LeaveBalance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $userName = Yii::$app->user->identity->username;
        $logStr = 'User ' . $userName . ' deleted leave_balance with id [' . $id . ']';
        Yii::info($logStr, 'leave');

        return $this->redirect(['index']);
    }

    /**
     * Finds the LeaveBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LeaveBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LeaveBalance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLeaveleft($empid, $leavetype, $year, $days)
    {
        $days = Leave::getmydaysLeft($empid, $leavetype, $year);

        if ($days == false) {
            $leave = LeaveType::find()
                        ->where(['id' => $leavetype])
                        ->one();
            $limit = $leave ? $leave->limit : null;
            if ($limit !== null) {
                $days = $limit;
            } else {
                $days = 0;
            }
            //$days = 0;
        }
        $results = [
            'days' => $days
        ];
        return Json::encode($results);
    }
}
