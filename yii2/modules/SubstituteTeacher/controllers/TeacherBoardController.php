<?php

namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use app\modules\SubstituteTeacher\models\TeacherBoardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * TeacherBoardController implements the CRUD actions for TeacherBoard model.
 */
class TeacherBoardController extends Controller
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
                    'appoint' => ['POST'],
                    'negate' => ['POST'],
                    'eligible' => ['POST']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'appoint', 'negate', 'eligible', 'dismiss', 'overview'],
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
     * Display a detailed overview of the teacher board. 
     * This action will query specific teacher boards; a GET request allows for
     * selection, a POST request displays information based on selections.
     * 
     */
    public function actionOverview($year = null, $specialisation = null, $board_type = null)
    {
        Url::remember('', 'teacherboardoverview');

        $over_query_params = ['TeacherBoardSearch' => []];
        if (!empty($year)) {
            $over_query_params['TeacherBoardSearch']['year'] = $year;
        }
        if (!empty($specialisation)) {
            $over_query_params['TeacherBoardSearch']['specialisation_id'] = $specialisation;
        }
        if (!empty($board_type)) {
            $over_query_params['TeacherBoardSearch']['board_type'] = $board_type;
        }

        $searchModel = new TeacherBoardSearch();
        $dataProvider = $searchModel->search(array_merge_recursive(Yii::$app->request->queryParams, $over_query_params));

        return $this->render('overview', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'year' => $year,
            'specialisation' => $specialisation,
            'board_type' => $board_type
        ]);
    }

    /**
     * Lists all TeacherBoard models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember('', 'teacherboardindex');

        $searchModel = new TeacherBoardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TeacherBoard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TeacherBoard();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(($index_url = Url::previous('teacherboardindex')) ? $index_url : ['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TeacherBoard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(($index_url = Url::previous('teacherboardindex')) ? $index_url : ['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TeacherBoard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(($index_url = Url::previous('teacherboardindex')) ? $index_url : ['index']);
    }

    /**
     * TODO / TBD log actions and possibly modify related/dependent information 
     * 
     * @return boolean whether the change (save) was succesful
     */
    protected function setStatus($id, $status)
    {
        $model = $this->findModel($id);
        $model->status = $status;
        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Mark a teacher board entry as dismissed.
     *
     * @param int $id The identity of the teacher board to mark as dismissed
     * @return mixed
     */
    public function actionDismiss($id)
    {
        if ($this->setStatus($id, Teacher::TEACHER_STATUS_DISMISSED)) {
            Yii::$app->session->setFlash('success', 'Πραγματοποιήθηκε αλλαγή της κατάστασης του αναπληρωτή.');
        } else {
            Yii::$app->session->setFlash('danger', 'Δεν πραγματοποιήθηκε αλλαγή της κατάστασης του αναπληρωτή.');
        }
        return $this->redirect(($index_url = Url::previous('teacherboardindex')) ? $index_url : ['index']);
    }

    /**
     * Mark a teacher board entry as appointed.
     *
     * @param int $id The identity of the teacher board to mark as appointed
     * @return mixed
     */
    public function actionAppoint($id)
    {
        if ($this->setStatus($id, Teacher::TEACHER_STATUS_APPOINTED)) {
            Yii::$app->session->setFlash('success', 'Πραγματοποιήθηκε αλλαγή της κατάστασης του αναπληρωτή.');
        } else {
            Yii::$app->session->setFlash('danger', 'Δεν πραγματοποιήθηκε αλλαγή της κατάστασης του αναπληρωτή.');
        }
        return $this->redirect(($index_url = Url::previous('teacherboardindex')) ? $index_url : ['index']);
    }

    /**
     * Mark a teacher board entry as negated.
     *
     * @param int $id The identity of the teacher board to mark as negated
     * @return mixed
     */
    public function actionNegate($id)
    {
        if ($this->setStatus($id, Teacher::TEACHER_STATUS_NEGATION)) {
            Yii::$app->session->setFlash('success', 'Πραγματοποιήθηκε αλλαγή της κατάστασης του αναπληρωτή.');
        } else {
            Yii::$app->session->setFlash('danger', 'Δεν πραγματοποιήθηκε αλλαγή της κατάστασης του αναπληρωτή.');
        }
        return $this->redirect(($index_url = Url::previous('teacherboardindex')) ? $index_url : ['index']);
    }

    /**
     * Mark a teacher board entry as eligible.
     *
     * @param int $id The identity of the teacher board to mark as eligible
     * @return mixed
     */
    public function actionEligible($id)
    {
        if ($this->setStatus($id, Teacher::TEACHER_STATUS_ELIGIBLE)) {
            Yii::$app->session->setFlash('success', 'Πραγματοποιήθηκε αλλαγή της κατάστασης του αναπληρωτή.');
        } else {
            Yii::$app->session->setFlash('danger', 'Δεν πραγματοποιήθηκε αλλαγή της κατάστασης του αναπληρωτή.');
        }
        return $this->redirect(($index_url = Url::previous('teacherboardindex')) ? $index_url : ['index']);
    }

    /**
     * Finds the TeacherBoard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TeacherBoard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TeacherBoard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
