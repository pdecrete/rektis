<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\models\FinanceKae;
use app\modules\finance\models\FinanceKaeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

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
                Yii::$app->session->addFlash('success', "Ο νέος ΚΑΕ δημιουργήθηκε επιτυχώς. Στα οικονομικά έτη που έχουν ήδη καθοριστεί πιστώσεις ΚΑΕ, έχει προστεθεί και ο νέος ΚΑΕ με μηδενική πίστωση.");
                return $this->redirect(['view', 'id' => $model->kae_id]);
            }
            catch(\Exception $exc){
                $transaction->rollBack();
                Yii::$app->session->addFlash('danger', "Αποτυχία δημιουργίας του νέου ΚΑΕ.");
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kae_id]);
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
