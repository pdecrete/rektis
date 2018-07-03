<?php

namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\PlacementPrint;
use app\modules\SubstituteTeacher\models\PlacementPrintSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Expression;

/**
 * PlacementPrintController implements the CRUD actions for PlacementPrint model.
 */
class PlacementPrintController extends Controller
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
                        'actions' => ['index'],
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
     * Lists all PlacementPrint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlacementPrintSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing PlacementPrint model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PlacementPrint model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleted = true;
        $model->deleted_at = new  Expression('CURRENT_TIMESTAMP()');

        if (!$model->save(false, ['deleted', 'deleted_at'])) {
            Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Placement print could not be marked as deleted.'));
        } else {
            Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'Placement print has been marked as deleted.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the PlacementPrint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlacementPrint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlacementPrint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested placement print does not exist.');
        }
    }
}
