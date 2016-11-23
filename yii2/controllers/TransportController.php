<?php

namespace app\controllers;

use Yii;
use app\models\Employee;
use app\models\TransportDistance;
use app\models\TransportMode;
use app\models\Transport;
use app\models\TransportPrint;
use app\models\TransportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use \PhpOffice\PhpWord\TemplateProcessor;
use yii\filters\AccessControl;

define ('fall', '0'); // τύπος αρχείου για εκτύπωση: ΟΛΑ
define ('fapproval', '1'); // τύπος αρχείου για εκτύπωση: ΕΓΚΡΙΣΗ ΜΕΤΑΚΙΝΗΣΗΣ
define ('fjournal', '2'); // τύπος αρχείου για εκτύπωση: ΗΜΕΡΟΛΟΓΙΟ ΜΕΤΑΚΙΝΗΣΗΣ
define ('fdocument', '3'); // τύπος αρχείου για εκτύπωση: ΔΙΑΒΙΒΑΣΤΙΚΟ ΜΕΤΑΚΙΝΗΣΗΣ

/**
 * TransportController implements the CRUD actions for Transport model.
 */
class TransportController extends Controller
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
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index', 'view'],
						'allow' => true,
						'roles' => ['@'],
					],
					[
						'allow' => true,
						'roles' => ['admin', 'user', 'transport_user'],
					],
				],
			],                                   
        ];
    }

    /**
     * Lists all Transport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transport model.
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
     * Creates a new Transport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transport();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
			$model->count_flag = TRUE;
			$model->days_applied = 0;
			$model->ticket_value = 0;
			$model->night_reimb = 0;		

			//Αν κάνω create από άλλο σημείο με employee_id (από καρτέλα εργαζομένου)
			if ((Yii::$app->request->isGet) && (Yii::$app->request->get('employee') !== NULL) ) 
			{
				$model->employee = Yii::$app->request->get('employee');
			};
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

	public function actionEmployeedef($empid) 
	{
		if (($emodel = Employee::findOne($empid)) !== null) {
			$base1 =  $emodel->work_base;
			$base2 =  $emodel->home_base;
			if ($base1 !== null) {
				if (($base2 !== null) && ($base1 !== $base2)) {
					$base = $base1 . ' ' . Yii::t('app', 'or') . ' ' . $base2;
				} else {
					$base = $base1;
				}			
			} elseif ($base2 !== null) {
				$base = $base2;
			} else {
				$base = null;
			}	
		} else {
			$base = null;
		}
		$results = [
			'base' => $base
		];
		return Json::encode($results);
	}


	public function actionCalculate($routeid, $modeid, $days, $ticket, $night_reimb) 
	{
		$klm = (float) (($tmodel = TransportDistance::findOne($routeid)) !== null) ? $tmodel->distance : 0.0;
		if (($mmodel = TransportMode::findOne($modeid)) !== null) {
			$mode_value =  $mmodel->value;
			$mode_out_limit = $mmodel->out_limit;
		} else {
			$mode_value =  0;
			$mode_out_limit = 0;
		}
		if ($days == null) {
			$days = 0;
		}				
		if ($ticket == null) {
			$ticket = 0;
		}				
		if ($night_reimb == null) {
			$night_reimb = 0;
		}				
		$day_reimb = 0;	
		if ($klm > Yii::$app->params['trans_out_limit']) {
			$klm_reimb = 2 * $klm * $mode_value; //χλμ αποζημίωση (*2 για επιστροφή)
			if ($klm <= $mode_out_limit) { //ημερήσια αποζημίωση
				$days_out = $days;
				$day_reimb = $days * Yii::$app->params['trans_day_limit1'] * Yii::$app->params['trans_day_reim'];
			} else {
				$days_out = $days;
				if ($days_out == 1) { // αυθημερόν
					$day_reimb = Yii::$app->params['trans_day_limit2'] * Yii::$app->params['trans_day_reim'];
				} else { 
					$day_reimb = $days * Yii::$app->params['trans_day_reim'];
				}		
			}
		} else {
			$days_out = 0;
			$klm_reimb = 0;
		}
		$code719 = $klm_reimb + $ticket;
		$code721 = $day_reimb;
		$code722 = $night_reimb;
		
		$reimbursement = $code719 + $code721 + $code722;
		$mtpy = round(Yii::$app->params['trans_mtpy'] * $code721, 2);
		$pay_amount = round($reimbursement - $mtpy, 2);
		$results = [
			'klm' => $klm,
			'klm_reimb' => $klm_reimb,
			'days_out' => $days_out,
			'day_reimb' => $day_reimb,
			'reimbursement' => $reimbursement,
			'mtpy' => $mtpy,
			'pay_amount' => $pay_amount,
			'code719' => $code719,
			'code721' => $code721,
			'code722' => $code722,
		];
		return Json::encode($results);
	}

    /**
     * Updates an existing Transport model.
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
     * Deletes an existing Transport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //        $this->findModel($id)->delete();
        $model = $this->findModel($id);
        $model->deleted = 1;
        if ($model->save()) {
            return $this->redirect(['index']);
        } else {
            throw new ServerErrorHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Transport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transport::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
        /**
     * Locate a Transport and generate / download a document for it. 
     * If a document is not already generated, it is generated. 
     * A link to download the document is provided in the view. 
     * 
     * @param integer $id
     * @throws NotFoundHttpException
     */
    public function actionPrint($id, $ftype)
    {	
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested transport is deleted.'));
        }
        if (($prints = $model->transportPrints) != null) {
            $filename = $prints[0]->filename;
            return $this->render('print', [
                    'model' => $model,
                    'filename' => $filename
			]);
        } else {          
            if ($ftype > fall) {
				$which = $ftype;
				$filename = $this->fixPrintDocument($model, $which);
			} else { // fall means all types...
				$which = fapproval;
				$filename = $this->fixPrintDocument($model, $which);				
/*				$which = fjournal;
				$filename2 = $this->fixPrintDocument($model, $which);				
				$which = fdocument;
				$filename3 = $this->fixPrintDocument($model, $which);				
*/			}
            Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully generated file on {date}.', ['date' => date('d/m/Y')]));          
			return $this->redirect(['print', 'id' => $id, 'ftype' => $ftype]);
        }
    }
    	
	/* Generate file
	 * @return Filename	 
	 */
	protected function fixPrintDocument($model, $which)
	{
		$filename = $this->generatePrintDocument($model, $which);
		$set = $this->setPrintDocument($model, $filename, $which);
		if (!$set) {
			throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested transport was not generated.') . $currentModel->id);
		}
		return $filename;
	}

     /**
     * Creates an exported print for the provided model. 
     * 
     * @param Transport $transportModel
     * @return String the generated file filename
     * @throws NotFoundHttpException
     */
    protected function generatePrintDocument($transportModel, $which)
    {
        $dts = date('YmdHis');
		if ($which == fapproval) { //ΕΓΚΡΙΣΗ ΜΕΤΑΚΙΝΗΣΗΣ
			$templatefilename = $transportModel->type0 ? $transportModel->type0->templatefilename1 : null;
			if ($templatefilename === null) {
				throw new NotFoundHttpException(Yii::t('app', 'There is no associated template file for this transport type.'));
			}
		} elseif ($which == fjournal) { //ΗΜΕΡΟΛΟΓΙΟ ΜΕΤΑΚΙΝΗΣΗΣ
			$templatefilename = $transportModel->type0 ? $transportModel->type0->templatefilename2 : null;
			if ($templatefilename === null) {
				throw new NotFoundHttpException(Yii::t('app', 'There is no associated template file for this transport type.'));
			}	
		}
        $exportfilename = Yii::getAlias("@vendor/admapp/exports/transports/{$dts}_{$templatefilename}");
        $templateProcessor = new TemplateProcessor(Yii::getAlias("@vendor/admapp/resources/transports/{$templatefilename}"));

		if ($which == fapproval) { //ΓΙΑ ΕΓΚΡΙΣΗ ΜΕΤΑΚΙΝΗΣΗΣ
	        $templateProcessor->setValue('YEAR_LIMIT', Yii::$app->params['trans_year_limit']);
			
			$empid = $transportModel->employee;
			$typeid = $transportModel->type;
			$year = date("Y", strtotime($transportModel->start_date));
			$trans_days = Employee::getTransportTypeTotal($empid, $typeid, $year); 
			
			$templateProcessor->setValue('TRANS_DAYS', $trans_days);
			$remaining = Yii::$app->params['trans_year_limit'] - $trans_days;
			$templateProcessor->setValue('REMAINING', $remaining);
		
			$k = 5; // Αριθμός ΕΧΟΝΤΑΣ ΥΠΟΨΗ
			$kae = '';
			if ($transportModel->fund1 !== null) {
				$k++;
				$fmodel = \app\models\TransportFunds::findone($transportModel->fund1);
				$templateProcessor->setValue('FUND1', $k . '. Τη με αριθ. ' . $fmodel->name . ' / ' .   Yii::$app->formatter->asDate($fmodel->date) . ' (ΑΔΑ: ' . $fmodel->ada . ') απόφαση ανάληψης υποχρέωσης.' );
				$kae = $fmodel->code . ' (KAE: ' . $fmodel->kae . ')';
			} else {
				$templateProcessor->setValue('FUND1','');		
			}
			if ($transportModel->fund2 !== null) {
				$k++;
				$fmodel = \app\models\TransportFunds::findone($transportModel->fund2);
				$templateProcessor->setValue('FUND2', $k . '. Τη με αριθ. ' . $fmodel->name . ' / ' .   Yii::$app->formatter->asDate($fmodel->date) . ' (ΑΔΑ: ' . $fmodel->ada . ') απόφαση ανάληψης υποχρέωσης.' );
				if ($kae == ''){
					$kae = $fmodel->kae;
				} else {
					$kae .= ', ' . $fmodel->code . ' (KAE: ' . $fmodel->kae . ')';
				}
			} else {
				$templateProcessor->setValue('FUND2','');		
			}
			if ($transportModel->fund3 !== null) {
				$k++;
				$fmodel = \app\models\TransportFunds::findone($transportModel->fund3);
				$templateProcessor->setValue('FUND3', $k . '. Τη με αριθ. ' . $fmodel->name . ' / ' .   Yii::$app->formatter->asDate($fmodel->date) . ' (ΑΔΑ: ' . $fmodel->ada . ') απόφαση ανάληψης υποχρέωσης.' );
				if ($kae == ''){
					$kae = $fmodel->kae;
				} else {
					$kae .= ', ' . $fmodel->code . ' (KAE: ' . $fmodel->kae . ')';
				}
			} else {
				$templateProcessor->setValue('FUND3','');		
			}
		}
		
		//ΓΙΑ ΟΛΟΥΣ ΤΟΥΣ ΤΥΠΟΥΣ
		$templateProcessor->setValue('DECISION_DATE', Yii::$app->formatter->asDate($transportModel->decision_protocol_date));
		$templateProcessor->setValue('DECISION_PROTOCOL', $transportModel->decision_protocol);
		$templateProcessor->setValue('TRANS_PERSON', Yii::$app->params['transportPerson']);
		$templateProcessor->setValue('TRANS_PHONE', Yii::$app->params['transportPhone']);
		$templateProcessor->setValue('TRANS_FAX', Yii::$app->params['transportFax']);
		        
        $templateProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['director_sign']);
        $templateProcessor->setValue('DIRECTOR', Yii::$app->params['director']);
        
        //Αν επιλέγεται ο Αναπληρωτής του Περιφερειακού
        //$templateProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['surrogate_sign']);
        //$templateProcessor->setValue('DIRECTOR', Yii::$app->params['surrogate']);
        
        if ($transportModel->application_protocol !== null) {
			if ($transportModel->application_date !== null) {
				$prot = $transportModel->application_protocol . ' / ' . Yii::$app->formatter->asDate($transportModel->application_date);
			} else {
				$prot = $transportModel->application_protocol; 
			} 
		} else {
			if ($transportModel->application_date !== null) {
				$prot = Yii::$app->formatter->asDate($transportModel->application_date);
			} else {
				$prot = '';
			}		
		}
        $templateProcessor->setValue('APPLICATION_PROTOCOL', $prot);
        if ($transportModel->employee0->serve_decision !== null) {
			if ($transportModel->employee0->serve_decision_date !== null) {
				$prot = $transportModel->employee0->serve_decision . ' / ' . Yii::$app->formatter->asDate($transportModel->employee0->serve_decision_date);
			} else {
				$prot = $transportModel->employee0->serve_decision; 
			} 
		} else {
			if ($transportModel->employee0->serve_decision_date !== null) {
				$prot = Yii::$app->formatter->asDate($transportModel->employee0->serve_decision_date);
			} else {
				$prot = '';
			}		
		}      
        $templateProcessor->setValue('PLACEMENT_NUM', $prot);
        $templateProcessor->setValue('KAE', $kae);      
     
		$i = 1;
        $templateProcessor->cloneRow('SURNAME', $i);
		$templateProcessor->setValue('SURNAME' . "#{$i}", $transportModel->employee0->surname);
        $templateProcessor->setValue('NAME' . "#{$i}", $transportModel->employee0->name);
        $templateProcessor->setValue('RANK' . "#{$i}", $transportModel->employee0->rank);
        $templateProcessor->setValue('SPEC' . "#{$i}", $transportModel->employee0->specialisation0->code . '(' . $transportModel->employee0->specialisation0->name . ')');

        if ($transportModel->start_date == $transportModel->end_date) {
			$templateProcessor->setValue('DATES' . "#{$i}", Yii::$app->formatter->asDate($transportModel->start_date));
		} else {
			$templateProcessor->setValue('DATES' . "#{$i}", Yii::$app->formatter->asDate($transportModel->start_date) . '-' . Yii::$app->formatter->asDate($transportModel->end_date));		
		}
        $templateProcessor->setValue('ROUTE' . "#{$i}", $transportModel->fromTo->name);
        $templateProcessor->setValue('MODE' . "#{$i}", $transportModel->mode0->name);
        $templateProcessor->setValue('DAYS' . "#{$i}", $transportModel->days_applied);
        $templateProcessor->setValue('BASE' . "#{$i}", $transportModel->base);
        $templateProcessor->setValue('SERVICE_SERVE' . "#{$i}", $transportModel->employee0->serviceServe->name);
        $templateProcessor->setValue('POSITION' . "#{$i}", $transportModel->employee0->position0->name);
        $templateProcessor->setValue('CAUSE' . "#{$i}", $transportModel->reason);

        $templateProcessor->saveAs($exportfilename);
        if (!is_readable($exportfilename)) {
            throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested transport was not generated.'));
        }

        return $exportfilename;
    }
    
    protected function setPrintDocument($transportModel, $filename, $which)
    {
        $new_print = new TransportPrint();
        $new_print->filename = basename($filename);
        $new_print->transport = $transportModel->id;
        $new_print->doctype = $which;       
        $ins = $new_print->insert();

        return $ins;
    }
    
	public function actionReprint($id, $ftype)
    {	
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested transport is deleted.'));
        }
		$which = $ftype;
        $filename = $this->fixPrintDocument($model, $which);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully generated file on {date}.', ['date' => date('d/m/Y')]));          
        return $this->render('print', [
                    'model' => $model,
                    'filename' => $filename
        ]);
    }

    public function actionDownload($id, $printid)
    {
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested transport is deleted.'));
        }
        if (($prints = $model->transportPrints) != null) {
//            $filename = $prints[0]->filename;
			$printmodel = TransportPrint::findOne($printid);
			$filename = $printmodel->filename;
        } else { // generate - set document if it does not exist
            $which = fapproval;
            $filename = $this->fixPrintDocument($model, $which);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully generated file on {date}.', ['date' => date('d/m/Y')]));          
        }

        // if file is STILL not generated, redirect to page
        if (!is_readable(TransportPrint::path($filename))) {
            return $this->redirect(['print', 'id' => $model->id, 'ftype' => fapproval ]);
        }

        // all well, send file 
        Yii::$app->response->sendFile(TransportPrint::path($filename));
    }

    public function actionDeleteprints($id)
    {
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested transport is deleted.'));
        }
		$delsuccess = $this->deleteAllPrints($model);
        if ($delsuccess == true) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Deletion completed succesfully.'));          
		} else {
			Yii::$app->session->setFlash('warning', Yii::t('app', 'Deletion did not complete succesfully.'));          
		}
        return $this->redirect(['view', 'id' => $model->id]);
    }

	protected function deleteAllPrints($transportModel)
	{
		$success = true;
		foreach ($transportModel->transportPrints as $print) {
			$unlink_filename = $print->path;
            if (file_exists($unlink_filename)) {
                unlink($unlink_filename);
            }
            if ($print->delete() == false) {
				$success = false;
			}
        }
        return $success;
	}

    
}















	/* Send multiple emails to $emails with $filename attached 
	 * @return Integer = number of emails successfully sent 
	 */  
/*	protected function sendEmail($filename, $emails)
    { 
		$subject = Yii::t('app', 'Leave decision post');
		$txtBody = Yii::$app->params['companyName'] . ' - ' . Yii::t('app', 'Automated leave email');
		$htmlBody = '<b>' . Yii::$app->params['companyName'] . ' - ' . Yii::t('app', 'Automated leave email') .'</b>';
		$messages = [];
		foreach ($emails as $email) {
			$messages[] = Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
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
				$logStr .= substr($e,0, $pos);	
			}
			Yii::info($logStr,'leave-email');
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
            if ($prints !== NULL) {
				if (count($emails) > 0) {
					$prints[0]->to_emails = implode(',', array_filter($emails, function($v){ return $v !== null; }));
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
		$simpleLeaveIDs = implode(',', array_filter($leaveIDs, function($v){ return $v !== null; }));
		$sendNum = $this->sendEmail(LeavePrint::path($filename), $emails);
 
		$userName = Yii::$app->user->identity->username;
		$simple_emails = implode(',', array_filter($emails, function($v){ return $v !== null; }));
		
        if ( isset($sendNum) && ($sendNum > 0)) {
			$updated = $this->updateEmailSent($emails, $model);
			$numUpd = count($updated);
			$idsUpd = implode(',', array_filter($updated, function($v){ return $v !== null; }));
			$logStr = 'User ' . $userName . ' sent leave_id [' . $model->id . ']. ConnectedLeaveIDs: [' . $simpleLeaveIDs . ']. To: [' . $simple_emails . ']. Emails sent: [' . $sendNum . ']. Leave_prints updated: [' . $numUpd . ']. Leave_prints IDs: [' . $idsUpd . ']. Filename: [' . $filename . '].';
			Yii::info($logStr,'leave-email');
			Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully emailed file on {date} to {num} recepients.', ['date' => date('d/m/Y'), 'num' => $sendNum]));
		} else {
			$logStr = 'User ' . $userName . ' tried to send leave_id [' . $model->id . ']. ConnectedLeaveIDs: [' . $simpleLeaveIDs . ']. To: [' . $simple_emails . ']. Emails sent: [' . $sendNum . ']. Leave_prints updated: [None]. Filename: [' . $filename . '].';	
			Yii::info($logStr,'leave-email');
			Yii::$app->session->setFlash('danger', Yii::t('app', 'The file was not emailed.'));		
		}

        return $this->redirect(['print', 'id' => $id]);
    }

*/
