<?php

namespace app\controllers;

use Yii;
use app\models\Leave;
use app\models\LeavePrint;
use app\models\LeaveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\VerbFilter;
use \PhpOffice\PhpWord\TemplateProcessor;
use yii\filters\AccessControl;

/**
 * LeaveController implements the CRUD actions for Leave model.
 */
class LeaveController extends Controller
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
//                    'print' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'download'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin', 'user', 'leave_user'],
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
        $searchModel = new LeaveSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates an exported print for the provided model.
     *
     * @param Leave $leaveModel
     * @return String the generated file filename
     * @throws NotFoundHttpException
     */
    protected function generatePrintDocument($leaveModel)
    {
        $dts = date('YmdHis');

        $templatefilename = $leaveModel->typeObj ? $leaveModel->typeObj->templatefilename : null;
        if ($templatefilename === null) {
            throw new NotFoundHttpException(Yii::t('app', 'There is no associated template file for this leave type.'));
        }
        $exportfilename = Yii::getAlias("@vendor/admapp/exports/{$dts}_{$templatefilename}");
        $templateProcessor = new TemplateProcessor(Yii::getAlias("@vendor/admapp/resources/{$templatefilename}"));

        $templateProcessor->setValue('DECISION_DATE', Yii::$app->formatter->asDate($leaveModel->decision_protocol_date));
        $templateProcessor->setValue('DECISION_PROTOCOL', $leaveModel->decision_protocol);

        $templateProcessor->setValue('LEAVE_PERSON', Yii::$app->params['leavePerson']);
        $templateProcessor->setValue('LEAVE_PHONE', Yii::$app->params['leavePhone']);
        $templateProcessor->setValue('LEAVE_FAX', Yii::$app->params['leaveFax']);

        $templateProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['director_sign']);
        $templateProcessor->setValue('DIRECTOR', Yii::$app->params['director']);
        //Αν επιλέγεται ο Αναπληρωτής του Περιφερειακού
        //$templateProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['surrogate_sign']);
        //$templateProcessor->setValue('DIRECTOR', Yii::$app->params['surrogate']);

        $extra = '';
        $reason_num = $leaveModel->typeObj ? $leaveModel->typeObj->reason_num : null;
        if ($reason_num !== null) {
            $k = $reason_num;
        } else {
            $k = 7; // τα έχοντας υπόψη της κανονικής άδειας
        }

        $sameDecisionModels = $leaveModel->allSameDecision();
        $all_count = count($sameDecisionModels);

        $templateProcessor->cloneRow('SURNAME', $all_count);
        for ($c = 0; $c < $all_count; $c++) {
            $i = $c + 1;
            $currentModel = $sameDecisionModels[$c];
            $templateProcessor->setValue('SURNAME' . "#{$i}", $currentModel->employeeObj->surname);
            $templateProcessor->setValue('NAME' . "#{$i}", $currentModel->employeeObj->name);
            $templateProcessor->setValue('FATHERSNAME' . "#{$i}", $currentModel->employeeObj->fathersname);
            $templateProcessor->setValue('DAYS' . "#{$i}", $currentModel->duration);
            $templateProcessor->setValue('START_DATE' . "#{$i}", Yii::$app->formatter->asDate($currentModel->start_date));
            $templateProcessor->setValue('END_DATE' . "#{$i}", Yii::$app->formatter->asDate($currentModel->end_date));
            $templateProcessor->setValue('APPLICATION_PROTOCOL' . "#{$i}", $currentModel->application_protocol . '/' . Yii::$app->formatter->asDate($currentModel->application_protocol_date, 'php:d-m-Y'));
            $rem = $currentModel->getmydaysLeft($currentModel->employee, $currentModel->type, date("Y", strtotime($currentModel->start_date)), $currentModel->start_date);
            $templateProcessor->setValue('REM' . "#{$i}", $rem);
            $templateProcessor->setValue('SERVICE_ORG' . "#{$i}", $currentModel->employeeObj->serviceOrganic->name);
            $templateProcessor->setValue('SERVICE_SERVE' . "#{$i}", $currentModel->employeeObj->serviceServe->name);
            $templateProcessor->setValue('POSITION' . "#{$i}", $currentModel->employeeObj->position0->name);
            $templateProcessor->setValue('ACCOMPANYING_DOCUMENT' . "#{$i}", $currentModel->accompanying_document_number);
            $templateProcessor->setValue('LEAVE_REASON' . "#{$i}", $currentModel->reason);
            $templateProcessor->setValue('LEAVE_TYPE' . "#{$i}", $currentModel->typeObj->name); // only on specific leaves...
            if (($currentModel->extra_reason1 !== '') && ($currentModel->extra_reason1 !== null)) {
                $k++;
                $extra .= $k . '. ' . $currentModel->extra_reason1 . '<w:br/>';
            }
            if (($currentModel->extra_reason2 !== '') && ($currentModel->extra_reason2 !== null)) {
                $k++;
                $extra .= $k . '. ' . $currentModel->extra_reason2 . '<w:br/>';
            }
            if (($currentModel->extra_reason3 !== '') && ($currentModel->extra_reason3 !== null)) {
                $k++;
                $extra .= $k . '. ' . $currentModel->extra_reason3 . '<w:br/>';
            }
        }
        $templateProcessor->setValue('EXTRA_REASON', $extra);

        $templateProcessor->saveAs($exportfilename);
        if (!is_readable($exportfilename)) {
            throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested leave was not generated.'));
        }

        return $exportfilename;
    }

    protected function setPrintDocument($leaveModel, $filename)
    {
        $new_print = new LeavePrint();
        $new_print->filename = basename($filename);
        $new_print->leave = $leaveModel->id;
        $ins = $new_print->insert();

        return $ins;
    }

    protected function deleteAllPrints($leaveModel)
    {
        //        LeavePrint::deleteAll(['leave' => $model->id]);
        foreach ($leaveModel->leavePrints as $print) {
            $unlink_filename = $print->path;
            if (file_exists($unlink_filename)) {
                unlink($unlink_filename);
            }
            $print->delete();
        }
    }

    /* Generate file
     * Set filename to all leave_prints if same decision
     * @return Filename
     */
    protected function fixPrintDocument($model)
    {
        $filename = $this->generatePrintDocument($model);

        $sameDecisionModels = $model->allSameDecision();
        $all_count = count($sameDecisionModels);

        for ($c = 0; $c < $all_count; $c++) {
            $currentModel = $sameDecisionModels[$c];
            $this->deleteAllPrints($currentModel); // Σβήνει όλες τις σχετικές με την άδεια εκτυπώσεις...
            $set = $this->setPrintDocument($currentModel, $filename);
            if (!$set) {
                throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested leave was not generated.') . $currentModel->id);
            }
        }
        return $filename;
    }

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
     * Locate a Leave and generate / download a document for it.
     * If a document is not already generated, it is generated.
     * A link to download the document is provided in the view.
     *
     * @param integer $id
     * @throws NotFoundHttpException
     */
    public function actionPrint($id)
    {
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested leave is deleted.'));
        }
        if (($prints = $model->leavePrints) != null) {
            $filename = $prints[0]->filename;
            return $this->render('print', [
                    'model' => $model,
                    'filename' => $filename
            ]);
        } else {
            $filename = $this->fixPrintDocument($model);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully generated file on {date}.', ['date' => date('d/m/Y')]));
            return $this->redirect(['print', 'id' => $id]);
        }
    }

    public function actionReprint($id)
    {
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested leave is deleted.'));
        }

        $filename = $this->fixPrintDocument($model);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully generated file on {date}.', ['date' => date('d/m/Y')]));
        return $this->render('print', [
                    'model' => $model,
                    'filename' => $filename
        ]);
    }


    /**
     * Displays a single Leave model.
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
     * Creates a new Leave model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Leave();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->typeObj->check == true) {
                $left = $model->daysLeft;

                if ($left == null) {
                    $leave = new Leave;
                    $days = $leave->getmydaysLeft(Yii::$app->request->get('employee'), $model->type, date('Y') - 1); // STATIC
                    $days = ($days > 0) ? $days : 0;

                    $left = $model->typeObj->limit + $days;
                }

                if ($model->duration > $left) {
                    $str = 'Ο υπάλληλος έχει υπόλοιπο ' . $left . ' ημέρες και προσπαθείτε να καταχωρήσετε ' . $model->duration . ' ημέρες. Παρακαλώ διορθώστε. ';
                    Yii::$app->session->setFlash('danger', $str);
                    return $this->render('create', ['model' => $model]);
                }
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $userName = Yii::$app->user->identity->username;
            $logStr = 'User ' . $userName . ' created leave with id [' . $model->id . ']';
            Yii::info($logStr, 'leave');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //Αν κάνω create από άλλο σημείο με employee_id (από καρτέλα εργαζομένου)
            if ((Yii::$app->request->isGet) && (Yii::$app->request->get('employee') !== null)) {
                $model->employee = Yii::$app->request->get('employee');
            };
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Leave model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->typeObj->check == true) {
                // check if days have changed and only then check left days
                if (($prev_days = (int)$model->getOldAttribute('duration')) != (int)$model->duration) {
                    $left = $model->daysLeft;
                    if ($left == null) {
                        $leave = new Leave;
                        $days = $leave->getmydaysLeft(Yii::$app->request->get('employee'), $model->type, date('Y') - 1); // STATIC
                        $days = ($days > 0) ? $days : 0;

                        $left = $model->typeObj->limit + $days;
                    }
                    if ($model->duration > $left) {
                        $str = 'Ο υπάλληλος έχει υπόλοιπο ' . $left . ' ημέρες και προσπαθείτε να καταχωρήσετε ' . $model->duration . ' ημέρες. Παρακαλώ διορθώστε. ';
                        Yii::$app->session->setFlash('danger', $str);
                        return $this->render('update', ['model' => $model]);
                    }
                }
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $userName = Yii::$app->user->identity->username;
            $logStr = 'User ' . $userName . ' updated leave with id [' . $model->id . ']';
            Yii::info($logStr, 'leave');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
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

    /* Send multiple emails to $emails with $filename attached
     * @return Integer = number of emails successfully sent
     */
    protected function sendEmail($filename, $emails)
    {
        $subject = Yii::t('app', 'Leave decision post');
        $txtBody = Yii::$app->params['companyName'] . ' - ' . Yii::t('app', 'Automated leave email');
        $htmlBody = '<b>' . Yii::$app->params['companyName'] . ' - ' . Yii::t('app', 'Automated leave email') .'</b>';
        $messages = [];
        foreach ($emails as $email) {
            $messages[] = Yii::$app->mailer->compose()
                // ->setFrom(Yii::$app->params['adminEmail'])
                ->setFrom(['noreply@pdekritis.gr' => 'ΠΔΕ Κρήτης'])
                ->setReplyTo('pdekritisweb@sch.gr')
                ->setSubject($subject)
                ->setTextBody($txtBody)
                ->setHtmlBody($htmlBody)
                ->attach($filename)
                ->setTo($email);
        }
        $num = -1;
        try {
            $num = Yii::$app->mailer->sendMultiple($messages);
        } catch (\Swift_TransportException $e) { // exception 'Swift_TransportException'
            $logStr = 'Swift_TransportException in leave-email-sending: ';
            $pos = strpos($e, 'Stack trace:');
            if ($pos>0) {
                $logStr .= substr($e, 0, $pos);
            }
            Yii::info($logStr, 'leave-email');
        }

        return  $num;//num of messages successfully sent
    }

    protected function updateEmailSent($emails, $model)
    {
        $ids = [];
        $k = 0;
        $sameDecisionModels = $model->allSameDecision();
        $all_count = count($sameDecisionModels);

        for ($c = 0; $c < $all_count; $c++) {
            $currentModel = $sameDecisionModels[$c];
            $prints = $currentModel->leavePrints;
            if ($prints !== null) {
                if (count($emails) > 0) {
                    $prints[0]->to_emails = implode(',', array_filter($emails, function ($v) {
                        return $v !== null;
                    }));
                }

                $prints[0]->send_ts = date("Y-m-d H:i:s");
                $upd = $prints[0]->save();
                if ($upd) {
                    $ids[$k] = $prints[0]->id;
                    $k++;
                }
            }
        }
        return $ids;
    }

    public function actionEmail($id)
    {
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested leave is deleted.'));
        }
        if (($prints = $model->leavePrints) != null) {
            $filename = $prints[0]->filename;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested leave was not found.'));
        }
        if (!is_readable(LeavePrint::path($filename))) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested leave was not found.') . LeavePrint::path($filename));
        }

        $emails = $model->getDecisionEmails();
        $leaveIDs = $model->getconnectedLeaveIDs();
        $simpleLeaveIDs = implode(',', array_filter($leaveIDs, function ($v) {
            return $v !== null;
        }));
        // $sendNum = $this->sendEmail(LeavePrint::path($filename), $emails);
        $sendNum = \app\modules\Email\controllers\PostmanController::send([
            'redirect_route' => [
                '/leave/print', 'id' => $model->id
            ],
            'template' => 'leave.mail.main',
            'template_data' => [
                '{DECISION_PROTOCOL}' => $model->decision_protocol,
                '{DECISION_DATE}' => Yii::$app->formatter->asDate($model->decision_protocol_date),
                '{LEAVE_PERSON}' => Yii::$app->params['leavePerson'],
                '{LEAVE_PHONE}' => Yii::$app->params['leavePhone'],
                '{LEAVE_FAX}' => Yii::$app->params['leaveFax'],
                '{LEAVE_TYPE}' => $model->typeObj->name,
            ],
            'files' => [
                LeavePrint::path($filename),
            ],
            'to' => [
                $model->employeeObj->email
            ],
            'cc' => [
                'spapad@outlook.com'
            ],
        ]);

        $userName = Yii::$app->user->identity->username;
        $simple_emails = implode(',', array_filter($emails, function ($v) {
            return $v !== null;
        }));

        if (isset($sendNum) && ($sendNum > 0)) {
            $updated = $this->updateEmailSent($emails, $model);
            $numUpd = count($updated);
            $idsUpd = implode(',', array_filter($updated, function ($v) {
                return $v !== null;
            }));
            $logStr = 'User ' . $userName . ' sent leave_id [' . $model->id . ']. ConnectedLeaveIDs: [' . $simpleLeaveIDs . ']. To: [' . $simple_emails . ']. Emails sent: [' . $sendNum . ']. Leave_prints updated: [' . $numUpd . ']. Leave_prints IDs: [' . $idsUpd . ']. Filename: [' . $filename . '].';
            Yii::info($logStr, 'leave-email');
            Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully emailed file on {date} to {num} recepients.', ['date' => date('d/m/Y'), 'num' => $sendNum]));
        } else {
            $logStr = 'User ' . $userName . ' tried to send leave_id [' . $model->id . ']. ConnectedLeaveIDs: [' . $simpleLeaveIDs . ']. To: [' . $simple_emails . ']. Emails sent: [' . $sendNum . ']. Leave_prints updated: [None]. Filename: [' . $filename . '].';
            Yii::info($logStr, 'leave-email');
            Yii::$app->session->setFlash('danger', Yii::t('app', 'The file was not emailed.'));
        }

        return $this->redirect(['print', 'id' => $id]);
    }
}
