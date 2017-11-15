<?php
namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\SubstituteTeacher\models\PlainFile;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Json;

/**
 * 
 */
class FileController extends Controller
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
                        'actions' => ['index', 'file-upload', 'file-delete', 'file-download'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Leave models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $searchModel = new LeaveSearch();
//        $searchModel->deleted = 0;
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $exportfilename = Yii::getAlias("@vendor/admapp/exports/{$dts}_{$templatefilename}");

        return $this->render('index', [
                'model' => new PlainFile()
//                'searchModel' => $searchModel,
//                'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFileUpload()
    {
        $model = new PlainFile();

        $uploadedFile = UploadedFile::getInstance($model, 'uploadfile');

        $directory = Yii::getAlias('@upload') . DIRECTORY_SEPARATOR . Yii::$app->session->id . DIRECTORY_SEPARATOR;
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }

        if ($uploadedFile) {
            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $uploadedFile->extension;
            $filePath = $directory . $fileName;
            if ($uploadedFile->saveAs($filePath)) {
                $path = \yii\helpers\Url::to(['file-download', 'file' => $fileName]);
//                    '/img/temp/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName;
                return Json::encode([
                        'files' => [
                            [
                                'name' => $fileName,
                                'size' => $uploadedFile->size,
                                'url' => $path,
                                'thumbnailUrl' => null, // $path,
                                'deleteUrl' => 'file-delete?name=' . $fileName,
                                'deleteType' => 'POST',
                            ],
                        ],
                ]);
            }
        }

        return '';
    }

    public function actionFileDelete($name)
    {
        $directory = Yii::getAlias('@upload') . DIRECTORY_SEPARATOR . Yii::$app->session->id;
        if (is_file($directory . DIRECTORY_SEPARATOR . $name)) {
            unlink($directory . DIRECTORY_SEPARATOR . $name);
        }

        $files = FileHelper::findFiles($directory);
        $output = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $path = \yii\helpers\Url::to(['file-download', 'file' => $fileName]);
//            $path = '/img/temp/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName;
            $output['files'][] = [
                'name' => $fileName,
                'size' => filesize($file),
                'url' => $path,
                'thumbnailUrl' => $path,
                'deleteUrl' => 'image-delete?name=' . $fileName,
                'deleteType' => 'POST',
            ];
        }
        return Json::encode($output);
    }

//        $exportfilename = Yii::getAlias("@vendor/admapp/exports/{$dts}_{$templatefilename}");
    public function actionFileDownload($id)
    {
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested leave is deleted.'));
        }

        if (($prints = $model->leavePrints) != null) {
            $filename = $prints[0]->filename;
        } else { // generate - set document if it does not exist
            $filename = $this->fixPrintDocument($model);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully generated fi'
                . 'le on {date}.', ['date' => date('d/m/Y')]));
        }

        // if file is STILL not generated, redirect to page
        if (!is_readable(LeavePrint::path($filename))) {
            return $this->redirect(['print', 'id' => $model->id]);
        }

        // all well, send file 
        Yii::$app->response->sendFile(LeavePrint::path($filename));
    }

    /**
     * Deletes (marks as deleted) an existing Leave model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ServerErrorHttpException if the model cannot be deleted
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
        $model = $this->findModel($id);
        $model->deleted = 1;
        if ($model->save()) {
            $userName = Yii::$app->user->identity->username;
            $logStr = 'User ' . $userName . ' deleted leave with id [' . $model->id . ']';
            Yii::info($logStr, 'leave');
            return $this->redirect(['index']);
        } else {
            throw new ServerErrorHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Leave model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Leave the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Leave::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
