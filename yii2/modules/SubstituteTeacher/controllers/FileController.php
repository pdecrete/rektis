<?php
namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

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
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
//                    [
//                        'actions' => ['view', 'download'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
                    [
                        'actions' => ['index'],
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
                'model' => new \app\modules\SubstituteTeacher\models\PlainFile()
//                'searchModel' => $searchModel,
//                'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImageUpload()
    {
        $model = new WhateverYourModel();

        $imageFile = UploadedFile::getInstance($model, 'image');

        $directory = Yii::getAlias('@frontend/web/img/temp') . DIRECTORY_SEPARATOR . Yii::$app->session->id . DIRECTORY_SEPARATOR;
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }

        if ($imageFile) {
            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $imageFile->extension;
            $filePath = $directory . $fileName;
            if ($imageFile->saveAs($filePath)) {
                $path = '/img/temp/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName;
                return Json::encode([
                        'files' => [
                            [
                                'name' => $fileName,
                                'size' => $imageFile->size,
                                'url' => $path,
                                'thumbnailUrl' => $path,
                                'deleteUrl' => 'image-delete?name=' . $fileName,
                                'deleteType' => 'POST',
                            ],
                        ],
                ]);
            }
        }

        return '';
    }

    public function actionImageDelete($name)
    {
        $directory = Yii::getAlias('@frontend/web/img/temp') . DIRECTORY_SEPARATOR . Yii::$app->session->id;
        if (is_file($directory . DIRECTORY_SEPARATOR . $name)) {
            unlink($directory . DIRECTORY_SEPARATOR . $name);
        }

        $files = FileHelper::findFiles($directory);
        $output = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $path = '/img/temp/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName;
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
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested leave is deleted.'));
        }

        if (($prints = $model->leavePrints) != null) {
            $filename = $prints[0]->filename;
        } else { // generate - set document if it does not exist
            $filename = $this->fixPrintDocument($model);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully generated file on {date}.', ['date' => date('d/m/Y')]));
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
