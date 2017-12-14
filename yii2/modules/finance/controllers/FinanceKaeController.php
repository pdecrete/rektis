<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\Module;
use app\modules\finance\components\Integrity;
use app\modules\finance\models\FinanceKae;
use app\modules\finance\models\FinanceKaeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\finance\models\FinanceKaecredit;

/**
 * FinanceKaeController implements the CRUD actions for FinanceKae model.
 */
class FinanceKaeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),               
                'rules' => [
                    [   'actions' => ['create', 'update'],
                        'allow' => false,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                                               return Integrity::isLocked(Yii::$app->session["working_year"]);
                                           },
                        'denyCallback' => function ($rule, $action) {
                                                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The action is not permitted! The year you are working on is locked."));
                                                return $this->redirect(['index']);
                                            }
                        ],
                        [   'actions' =>['index', 'create', 'update'],
                            'allow' => true,
                            'roles' => ['@'],
                        ]
                    ]
                ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all FinanceKae models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceKaeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FinanceKae model.
     * @param integer $id
     * @return mixed
     */
    /*public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/
    
    /*
    public function actionDelete($id)
    {
        if(!$this->findModel($id)->delete()){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The deletion of RCN {id} failed.", ['id' => $id]));
            return $this->redirect(['index']);
        }
        Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The RCN {id} was deleted succesfully.", ['id' => $id]));
        return $this->redirect(['index']);
    }
    */
    
    /**
     * Creates a new FinanceKae model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceKae();

        if ($model->load(Yii::$app->request->post())){ 
            try{
                $transaction = Yii::$app->db->beginTransaction();
                if(!$model->save()) throw new \Exception();
                $financeYearCredits = FinanceKaecredit::find()->select('year')->distinct()->all();
                foreach ($financeYearCredits as $financeYear)
                {                    
                    $newKAEcredit = new FinanceKaecredit();
                    $newKAEcredit->kae_id = $model->kae_id;
                    $newKAEcredit->kaecredit_amount = 0;
                    $newKAEcredit->kaecredit_date = date("Y-m-d H:i:s");
                    $newKAEcredit->year = $financeYear->year;
                    if(!$newKAEcredit->save()) throw new \Exception();
                }
                $transaction->commit();
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The new RCN was created succesfully. The new RCN has been added with 0 credit to the financial years that have already defined credits for the RCNs."));
                return $this->redirect(['index', 'id' => $model->kae_id]);
            }
            catch(\Exception $exc){
                $transaction->rollBack();
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failed to create new RCN"));
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceKae model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->save()){
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failed to update the RCN {id}", ['id' => $id]));
                return $this->redirect(['index', 'id' => $model->kae_id]);
            }
            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The RCN {id} was updated successfully", ['id' => $id]));
            return $this->redirect(['index', 'id' => $model->kae_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Finds the FinanceKae model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceKae the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceKae::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('modules/finance/app', 'The requested page does not exist.'));
        }
    }
}
