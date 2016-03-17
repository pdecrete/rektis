<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'undelete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['account', 'updateaccount'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Displays current User model.
     * @return mixed
     */
    public function actionAccount()
    {
        return $this->render('account', ['model' => $this->findModel(Yii::$app->user->getId())]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario(User::SCENARIO_UPDATE);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Ολοκληρώθηκε με επιτυχία η ενημέρωση των στοιχείων.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('danger', 'Δεν πραγματοποιήθηκε ενημέρωση των στοιχείων.');
                return $this->render('update', ['model' => $model]);
            }
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionUpdateaccount()
    {
        $model = $this->findModel(Yii::$app->user->getId());
        $model->setScenario(User::SCENARIO_UPDATE);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $auth = Yii::$app->authManager;
                    $auth->revokeAll($model->id);
                    foreach ($model->activeroles as $role) {
                        $role_obj = $auth->getRole($role);
                        $auth->assign($role_obj, $model->id);
                    }
                    Yii::$app->session->setFlash('success', 'Ολοκληρώθηκε με επιτυχία η ενημέρωση των στοιχείων σας.');
                    $transaction->commit();
                    return $this->redirect(['account']);
                } else {
                    Yii::$app->session->setFlash('danger', 'Δεν πραγματοποιήθηκε ενημέρωση των στοιχείων σας.');
                    $transaction->rollBack();
                    return $this->render('updateaccount', ['model' => $model]);
                }
            } catch (Exception $e) {
                Yii::$app->session->setFlash('danger', 'Δεν πραγματοποιήθηκε ενημέρωση των στοιχείων σας.');
                $transaction->rollBack();
            }



//            if ($model->save()) {
//                Yii::$app->session->setFlash('success', 'Ολοκληρώθηκε με επιτυχία η ενημέρωση των στοιχείων σας.');
//                return $this->redirect(['account']);
//            } else {
//                Yii::$app->session->setFlash('danger', 'Δεν πραγματοποιήθηκε ενημέρωση των στοιχείων σας.');
//                return $this->render('updateaccount', ['model' => $model]);
//            }
        } else {
            return $this->render('updateaccount', ['model' => $model]);
        }
    }

    /**
     * Set the status field of a User model.
     * @param integer $id
     * @param integer $status
     * @return boolean
     */
    protected function userSetStatus($id, $status)
    {
        $user = $this->findModel($id);
        $user->status = $status;
        return $user->save();
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
        if (!$this->userSetStatus($id, User::STATUS_DELETED)) {
            throw new HttpException(400, 'Δεν ολοκληρώθηκε η ενημέρωση των στοιχείων.');
        }
        Yii::$app->session->setFlash('success', 'Ο χρήστης απενεργοποιήθηκε.');

        return $this->redirect(['index']);
    }

    public function actionUndelete($id)
    {
        if (!$this->userSetStatus($id, User::STATUS_ACTIVE)) {
            throw new HttpException(400, 'Δεν ολοκληρώθηκε η ενημέρωση των στοιχείων.');
        }
        Yii::$app->session->setFlash('success', 'Ο χρήστης ενεργοποιήθηκε.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Το αντικείμενο χρήστη που ζητήθηκε δεν υπάρχει.');
        }
    }

}
