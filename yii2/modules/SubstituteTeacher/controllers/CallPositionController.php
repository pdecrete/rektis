<?php
namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\CallPosition;
use app\modules\SubstituteTeacher\models\CallPositionSearch;
use app\modules\SubstituteTeacher\models\PositionSearch;
use app\modules\SubstituteTeacher\models\Call;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * CallPositionController implements the CRUD actions for CallPosition model.
 */
class CallPositionController extends Controller
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
                    'distribution-remove' => ['POST'],
                    'distribution-group' => ['POST'],
                    'distribution-ungroup' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'spedu_user'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CallPosition models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CallPositionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CallPosition model.
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
     * Creates new CallPosition models for a specified call.
     *
     * @return mixed
     */
    public function actionDistribute($call)
    {
        if (($callModel = Call::findOne($call)) == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $positionsSearchModel = new PositionSearch();
        $positionsDataProvider = $positionsSearchModel->search(Yii::$app->request->queryParams, 10);

        $callPositionsSearchModel = new CallPositionSearch();
        $callPositionsDataProvider = $callPositionsSearchModel->search(
            array_merge(Yii::$app->request->queryParams, [
            'CallPositionSearch' => [
                'call_id' => $call
        ]])
        );
        $all = $callPositionsDataProvider->query->all();

        if (!empty($all)) {
            $positionsDataProvider->query->andWhere(['not', ['id' => array_map(function ($m) {
                            return $m->position_id;
                        }, $all)]]);
        }

//        //        echo '<pre>',
//        print_r(array_map(function ($m) {
//                return $m->id;
//            }, $all), true),
//        '<br/>',
//        print_r($_POST, true),
//        '</pre>';
//        die();
        // save url for coming back again 
        Url::remember('', 'distribute');

        return $this->render('distribute', [
                'callModel' => $callModel,
                'positionsSearchModel' => $positionsSearchModel,
                'positionsDataProvider' => $positionsDataProvider,
                'callPositionsSearchModel' => $callPositionsSearchModel,
                'callPositionsDataProvider' => $callPositionsDataProvider,
        ]);
    }

    public function actionDistributionAdd()
    {
        $model = new CallPosition();

        if ($model->load(Yii::$app->request->get()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('substituteteacher', 'The position has been added to the distribution.'));
        } else {
            $msg = '';
            $errors = $model->getErrors();
            array_walk_recursive($errors, function($v) use (&$msg) {
                $msg .= " {$v}";
            });
            Yii::$app->session->addFlash('danger', Yii::t('substituteteacher', 'The position has not been added to the distribution.') .
                $msg);
        }
        $url = Url::previous('distribute');
        return $this->redirect($url ? $url : ['distribute']);
    }

    /**
     * Deletes an existing CallPosition model.
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionDistributionRemove($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->addFlash('success', Yii::t('substituteteacher', 'The position has been removed from the distribution.'));

        $url = Url::previous('distribute');
        return $this->redirect($url ? $url : ['distribute']);
    }

    /**
     * Group a existing CallPositions together.
     * Locates the next group id (counter) and sets it for all models passed
     * via the group_ids parameter
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionDistributionGroup($call)
    {
        $group_ids = (array) Yii::$app->request->post('group_ids', []);
        if (count($group_ids) < 2) {
            Yii::$app->session->addFlash('danger', Yii::t('substituteteacher', 'Grouping requires at least two positions.'));
        } else {
            $models = CallPosition::find()
                ->andWhere([
                    'call_id' => $call,
                    'id' => $group_ids
                ])
                ->all();

            if (empty($models)) {
                Yii::$app->session->addFlash('danger', Yii::t('substituteteacher', 'No call positions located.'));
            } else {
                // figure out next group id 
                $max_group_id = CallPosition::find()
                    ->andWhere(['call_id' => $call])
                    ->max('[[group]]');
                $group_id = (int) $max_group_id + 1;
                // 
                // TODO Add checks for grouping
                //
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($models as $model) {
                        $model->group = $group_id;
                        if (!$model->save()) {
                            throw new Exception(Yii::t('substituteteacher', 'An error occured while grouping positions.'));
                        }
                    }
                    $transaction->commit();
                    \Yii::$app->session->addFlash('success', Yii::t('substituteteacher', 'Positions grouped.'));
                } catch (\Exception $ex) {
                    $transaction->rollBack();
                    \Yii::$app->session->addFlash('danger', '<h3>' . Yii::t('substituteteacher', 'Position grouping failed') . '</h3>');
                    \Yii::$app->session->addFlash('danger', $ex->getMessage());
                }
            }
        }

        $url = Url::previous('distribute');
        return $this->redirect($url ? $url : ['distribute']);
    }

    /**
     * Place an existing CallPosition model out of groups
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionDistributionUngroup($id)
    {
        $model = $this->findModel($id);
        $model->group = 0;
        // TODO consider removing group from other models if only one remains in group
        if ($model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('substituteteacher', 'The position has been removed from the group.'));
        } else {
            Yii::$app->session->addFlash('danger', Yii::t('substituteteacher', 'The position has not been removed from the group.'));
        }

        $url = Url::previous('distribute');
        return $this->redirect($url ? $url : ['distribute']);
    }

    /**
     * Deletes an existing CallPosition model.
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
     * Finds the CallPosition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CallPosition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CallPosition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
