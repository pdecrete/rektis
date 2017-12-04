<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\models\FinanceKaecreditpercentage;
use app\modules\finance\models\FinanceKaecreditpercentageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\finance\components\Money;

/**
 * FinanceKaecreditpercentageController implements the CRUD actions for FinanceKaecreditpercentage model.
 */
class FinanceKaecreditpercentageController extends Controller
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
     * Lists all FinanceKaecreditpercentage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceKaecreditpercentageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FinanceKaecreditpercentage model.
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
     * Creates a new FinanceKaecreditpercentage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceKaecreditpercentage();
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kaeperc_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceKaecreditpercentage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $kae = $model->getKae()->one();
        $kaecredit = $model->getKaecredit()->one();
        
        if ($model->load(Yii::$app->request->post())){
            try{                
                $oldmodelcredit = $this->findModel($id)->getKaecredit()->one();                
                $currentPercentSum = FinanceKaecreditpercentage::getKaeCreditSumPercentage($model->kaecredit_id);

                $model->kaeperc_percentage = Money::toDbPercentage($model->kaeperc_percentage);
                echo (int)$model->kaeperc_percentage + (int)$currentPercentSum - (int)$oldmodelcredit); die();
                if($model->kaeperc_percentage > 10000 || $model->kaeperc_percentage < 0 || 
                    ((int)$model->kaeperc_percentage + (int)$currentPercentSum - (int)$oldmodelcredit) > 10000) throw new \Exception();
                if(!$model->save()) throw new \Exception();
                Yii::$app->session->addFlash('success', "Οι αλλαγές σας αποθηκεύτηκαν επιτυχώς.");
                return $this->redirect(['/finance/finance-kaecreditpercentage/index']);
            }
            catch(\Exception $exc){
                Yii::$app->session->addFlash('danger', "Αποτυχία αποθήκευσης των αλλαγών σας. Ελέγξτε την εγκυρότητα των στοιχείων που εισάγατε (π.χ. ποσοστό > 100%) ή επικοινωνήστε με το διαχειριστή.");
                return $this->redirect(['/finance/finance-kaecreditpercentage/index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'kae' => $kae,
                'kaecredit' => $kaecredit
            ]);
        }
    }

    /**
     * Deletes an existing FinanceKaecreditpercentage model.
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
     * Finds the FinanceKaecreditpercentage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceKaecreditpercentage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceKaecreditpercentage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
