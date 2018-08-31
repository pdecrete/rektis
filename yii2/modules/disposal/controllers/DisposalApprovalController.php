<?php

namespace app\modules\disposal\controllers;

use Exception;
use Yii;
use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\DisposalApproval;
use app\modules\disposal\models\DisposalApprovalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\disposal\models\Disposal;
use app\modules\disposal\models\DisposalDisposalapproval;

/**
 * DisposalApprovalController implements the CRUD actions for DisposalApproval model.
 */
class DisposalApprovalController extends Controller
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
     * Lists all DisposalApproval models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DisposalApprovalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DisposalApproval model.
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
     * Creates a new DisposalApproval model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $disposal_ids = Yii::$app->request->post('selection');
        //echo "<pre>"; print_r($disposal_ids); echo "</pre>";
        if (count($disposal_ids) == 0) {
            Yii::$app->session->addFlash('info', DisposalModule::t('modules/disposal/app', "Please select at least one disposal."));
            return $this->redirect(['index']);
        }
        
        $model = new DisposalApproval();
        $disposals_models = array();
        $disposalapproval_models = array();
        foreach ($disposal_ids as $index=>$disposal_id){            
            $disposals_models[$index] = Disposal::find()->where(['disposal_id' => $disposal_id])->one();
            $disposalapproval_models = new DisposalDisposalapproval();
        }
        
        
        
        echo "<pre>"; print_r($disposals_models); echo "</pre>"; die();
        
        try {
            if($model->load(Yii::$app->request->post())) {
                if(!$model->save())
                    throw new Exception();

                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The approval of the disposals was created successfully."));
                return $this->redirect(['index']);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                    'disposals_models' => $disposals_models,
                    'disposalapproval_models' => $disposalapproval_models
                ]);
            }
        }
        catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', $exc->getMessage());
            return $this->render('create', [
                'model' => $model,
                'disposals_models' => $disposals_models,
                'disposalapproval_models' => $disposalapproval_models
            ]);
        }   
    }

    /**
     * Updates an existing DisposalApproval model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->approval_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DisposalApproval model.
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
     * Finds the DisposalApproval model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DisposalApproval the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DisposalApproval::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
