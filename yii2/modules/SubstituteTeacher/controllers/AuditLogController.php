<?php

namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\AuditLog;
use app\modules\SubstituteTeacher\models\AuditLogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * AuditLogController implements the CRUD actions for AuditLog model.
 */
class AuditLogController extends Controller
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
                    [
                        'actions' => ['index', 'view', 'next', 'previous'],
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
     * Lists all AuditLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuditLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuditLog model.
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
     * Figure out the next newer audit log entry and redirect to that.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionNext($id)
    {
        $next = $this->locateNext($id, 'new');
        if (!empty($next)) {
            return $this->redirect(['view', 'id' => $next->id]);
        } else {
            Yii::$app->session->addFlash('info', Yii::t('substituteteacher', 'This is the newest entry'));
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    /**
     * Figure out the next older audit log entry and redirect to that.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionPrevious($id)
    {
        $next = $this->locateNext($id, 'old');
        if (!empty($next)) {
            return $this->redirect(['view', 'id' => $next->id]);
        } else {
            Yii::$app->session->addFlash('info', Yii::t('substituteteacher', 'This is the oldest entry'));
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    /**
     *
     * @param integer $id
     * @param string $comp use 'old', 'new' to get the next older or newer record
     */
    protected function locateNext($id, $comp)
    {
        if ($comp == 'new') {
            $comp_op = '>';
            $sort_op = SORT_ASC;
        } elseif ($comp == 'old') {
            $comp_op = '<';
            $sort_op = SORT_DESC;
        } else {
            return null;
        }

        return AuditLog::find()
            ->andWhere([$comp_op, 'id', $id])
            ->orderBy(['id' => $sort_op])
            ->limit(1)
            ->one();
    }

    /**
     * Finds the AuditLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AuditLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuditLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('substituteteacher', 'The requested audit log entry does not exist.'));
        }
    }
}
