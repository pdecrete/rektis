<?php
namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\SubstituteTeacherFile;
use app\modules\SubstituteTeacher\models\SubstituteTeacherFileSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Json;

/**
 * SubstituteTeacherFileController implements the CRUD actions for SubstituteTeacherFile model.
 */
class SubstituteTeacherFileController extends Controller
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
                    'file-upload' => ['POST'],
                    'file-delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
//                    [
//                        'actions' => ['view', 'download'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
                    [
                        'actions' => ['index', 'upload', 'file-upload', 'file-delete', 'file-download', 'import'],
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
     * Lists all SubstituteTeacherFile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubstituteTeacherFileSearch();
        $dataProvider = $searchModel->search(
            \yii\helpers\ArrayHelper::merge(Yii::$app->request->queryParams, [
                'SubstituteTeacherFileSearch' => ['deleted' => SubstituteTeacherFile::FILE_ACTIVE]
        ])
        );

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all SubstituteTeacherFile models to select one to import
     *
     * @param string $route The route to forward after selection; route will also receive a parameter "file_id"
     * @return mixed
     */
    public function actionImport($route, $type)
    {
        $searchModel = new SubstituteTeacherFileSearch();
        $dataProvider = $searchModel->search(
            \yii\helpers\ArrayHelper::merge(Yii::$app->request->queryParams, [
                'SubstituteTeacherFileSearch' => ['deleted' => SubstituteTeacherFile::FILE_ACTIVE]
        ])
        );

        return $this->render('import', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'route' => $route,
                'type' => $type
        ]);
    }

    /**
     * Display the upload form for files
     *
     * @return mixed
     */
    public function actionUpload()
    {
        return $this->render('upload', [
                'model' => new SubstituteTeacherFile()
        ]);
    }

    /**
     * Used to upload a file to server
     *
     * @return mixed
     */
    public function actionFileUpload()
    {
        $model = new SubstituteTeacherFile(['scenario' => SubstituteTeacherFile::SCENARIO_UPLOAD_FILE]);

        $uploadedFile = UploadedFile::getInstance($model, 'uploadfile');

        $directory = $model->getSavepath();
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }

        if ($uploadedFile) {
            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $uploadedFile->extension;
            $filePath = $directory . DIRECTORY_SEPARATOR . $fileName;
            if ($uploadedFile->saveAs($filePath)) {

                // save model to db
                $model->title = 'Αρχείο δεδομένων χωρίς τίτλο';
                $model->original_filename = $uploadedFile->name;
                $model->filename = $fileName;
                $model->size = $uploadedFile->size;
                $model->mime = $uploadedFile->type;
                $model->uploadfile = $fileName; // no need though

                if ($model->save()) {
                    // notify of success
                    $path = \yii\helpers\Url::to(['file-download', 'id' => $model->id]);
                    return Json::encode([
                            'files' => [
                                [
                                    'name' => $fileName,
                                    'size' => $uploadedFile->size,
                                    'url' => $path,
                                    'thumbnailUrl' => null,
                                    'deleteUrl' => 'file-delete?id=' . $model->id,
                                    'deleteType' => 'POST',
                                ],
                            ],
                    ]);
                } else {
                    // also remove uploaded file
                    @unlink($filePath);
                }
            }
        }

        return '';
    }

    /**
     * Delete an uploaded file
     *
     * @param integer $id
     * @return mixed
     */
    public function actionFileDelete($id)
    {
        $model = $this->findModel($id);
        $filename = $model->getFullFilepath();
        $model->deleted = SubstituteTeacherFile::FILE_DELETED;
        if ($model->save()) {
            if (is_file($filename)) {
                @unlink($filename);
            }

            $output = [];
//            $output['files'] = array_map(function ($m) {
//                return [
//                    'name' => $m->filename,
//                    'size' => $m->size,
//                    'url' => \yii\helpers\Url::to(['file-download', 'id' => $m->id]),
//                    'thumbnailUrl' => null,
//                    'deleteUrl' => \yii\helpers\Url::to(['file-delete', 'id' => $m->id]),
//                    'deleteType' => 'POST',
//                ];
//            }, SubstituteTeacherFile::find()->active()->all());
            return Json::encode($output);
        } else {
            return '';
        }
    }

    /**
     *
     * @param integer $id
     */
    public function actionFileDownload($id)
    {
        $model = $this->findModel($id);
        Yii::$app->response->sendFile($model->getFullFilepath());
    }

    /**
     * Displays a single SubstituteTeacherFile model.
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
     * Updates an existing SubstituteTeacherFile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SubstituteTeacherFile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $filename = $model->getFullFilepath();
        $model->deleted = SubstituteTeacherFile::FILE_DELETED;
        if ($model->save()) {
            if (is_file($filename)) {
                @unlink($filename);
            }
            Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'File deleted.'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'The file was not deleted.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the SubstituteTeacherFile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubstituteTeacherFile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubstituteTeacherFile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
