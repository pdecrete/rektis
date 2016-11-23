<?php

namespace app\controllers;

use Yii;
use app\models\LeavePrint;
use app\models\LeavePrintSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * LeavePrintController implements the CRUD actions for LeavePrint model.
 */
class LeavePrintController extends Controller
{

    public function behaviors()
    {
        return [
/*            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'user', 'leave_user'],
                    ],
                ],
            ],*/
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index'],
						'allow' => true,
						// Allow all registered users to index
						'roles' => ['@'],
					],
				],
			],                                   
        ];
    }

    /**
     * Lists all LeavePrint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LeavePrintSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the LeavePrint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LeavePrint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LeavePrint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
