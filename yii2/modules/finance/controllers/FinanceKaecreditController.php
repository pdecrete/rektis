<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\models\FinanceKaecredit;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\finance\models\FinanceKae;
use yii\base\Model;
use yii\base\Exception;
use app\modules\finance\components\Money;

/**
 * FinanceKaecreditController implements the CRUD actions for FinanceKaecredit model.
 */
class FinanceKaecreditController extends Controller
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
     * Lists all FinanceKaecredit models.
     * @return mixed
     */
    public function actionIndex()
    {
        //$searchModel = new FinanceKaecreditSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider = (new \yii\db\Query())
                        ->select(['tblcredit.kae_id', 'kae_title', '(TRUNCATE(kaecredit_amount/100, 2)) as credit', 'kaecredit_date', 'kaecredit_updated'])
                        ->from(['admapp_finance_kae tblkae' , 'admapp_finance_kaecredit tblcredit'])
                        ->where('tblcredit.kae_id = tblkae.kae_id')
                        ->andWhere(['year' => Yii::$app->session["working_year"]])                        
                        ->all();
        
        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

 /*   public function actionSetKaeCredits()
    {
        
    }*/
    
    /**
     * Displays a single FinanceKaecredit model.
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
     * Creates a new FinanceKaecredit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $isYearKAEsSet = FinanceKaecredit::find()->where(['year' => Yii::$app->session["working_year"]])->count("kae_id"); 
        if($isYearKAEsSet)
            return $this->redirect(['/finance/finance-kaecredit/update']);
        
        $allkaes = FinanceKae::find()->asArray()->all();
        
        $kaecredits = array();
        $kaetitles = array();
        $i = 0;
        foreach ($allkaes as $kae){
            $kaetitles[$i] = $kae['kae_title'];
            $kaecredits[$i] = new FinanceKaecredit();
            $kaecredits[$i]->kae_id = $kae['kae_id'];
            $kaecredits[$i]->kaecredit_amount = 0;
            $kaecredits[$i]->kaecredit_date = date("Y-m-d H:i:s");
            $kaecredits[$i++]->year = Yii::$app->session["working_year"];
        }
    
        if(($userdata = Model::loadMultiple($kaecredits, Yii::$app->request->post()))){
            $this->saveModels($kaecredits);            
            return $this->redirect(['/finance/finance-kaecredit']);
        } 
        else {
            return $this->render('create', [
                'model' => $kaecredits,
                'kaetitles' => $kaetitles
            ]);
        }
    }

    
    private function saveModels($kaecredits)
    {   
        foreach ($kaecredits as $kaecredit){
            $kaecredit->kaecredit_amount = Money::toCents($kaecredit->kaecredit_amount);
            $old_credit = FinanceKaecredit::find()->where(["kaecredit_id" => $kaecredit->kaecredit_id])->one();
            if(!($old_credit->kaecredit_amount == $kaecredit->kaecredit_amount))
                $kaecredit->kaecredit_updated = date("Y-m-d H:i:s");
        }

        if(Model::validateMultiple($kaecredits)){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                foreach($kaecredits as $kaecredit)
                    
                    if(!$kaecredit->save()) throw new Exception();
                    $transaction->commit();
                    Yii::$app->session->setFlash('info', "Οι επιλογές σας αποθηκεύτηκαν επιτυχώς.");
                    return $this->redirect(['/finance/finance-kaecredit']);                  
            }
            catch(Exception $e){
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', "Οι ενέργειά σας δεν ολοκληρώθηκε με επιτυχία. Υπήρξε σφάλμα κατά την αποθήκευση στη βάση δεδομένων.");
                return $this->redirect(['/finance/finance-kaecredit']);
            }
        }
        else
            throw new Exception("Data validation failure.");
    }
    
    /**
     * Updates an existing FinanceKaecredit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $allkaes = FinanceKae::find()->asArray()->all();
        foreach($allkaes as $index => $kae)
            $kaes[$index] = $kae['kae_title'];
        //echo "<pre>"; print_r($kaes); echo "</pre>";die();
        //$kaes = FinanceKae::find()->all();
        $kaecredits = FinanceKaecredit::find()->where(['year' => Yii::$app->session["working_year"]])->all();
        
        if(($userdata = Model::loadMultiple($kaecredits, Yii::$app->request->post()))){
            $this->saveModels($kaecredits);
            return $this->redirect(['/finance/finance-kaecredit']);
        } else {
            return $this->render('update', [
                'model' => $kaecredits,
                'kaetitles' => $kaes
            ]);
        }
    }

    /**
     * Deletes an existing FinanceKaecredit model.
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
     * Finds the FinanceKaecredit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceKaecredit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceKaecredit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
