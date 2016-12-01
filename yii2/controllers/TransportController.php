<?php

namespace app\controllers;

use Yii;
use app\models\Employee;
use app\models\TransportDistance;
use app\models\TransportMode;
use app\models\Transport;
use app\models\TransportPrint;
use app\models\TransportPrintConnection;
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
				$empid = Yii::$app->request->get('employee');
				$model->employee = $empid;
				// Αρχικοποιώ με τα στοιχεία της τελευταίας έγκρισης για διευκόλυνση του διοικητικού υπαλλήλου 
				$lastTrans = Transport::lastTransport($empid);
				if ($lastTrans !== null) {
					$model->type = $lastTrans->type;
					$model->decision_protocol = $lastTrans->decision_protocol;
					$model->decision_protocol_date = $lastTrans->decision_protocol_date;
					$model->application_protocol = $lastTrans->application_protocol;
					$model->application_protocol_date = $lastTrans->application_protocol_date;
					$model->application_date = $lastTrans->application_date;
					$model->fund1 = $lastTrans->fund1;
					$model->fund2 = $lastTrans->fund2;
					$model->fund3 = $lastTrans->fund3;	
					$model->base = $lastTrans->base;	
					$model->mode = $lastTrans->mode;	
				} else {
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
					$model->base = $base;			
				}
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
        if (($prints = $model->transportPrintConnections) != null) {
            $filename = $prints[0]->transportPrint0->filename;
            return $this->render('print', [
                    'model' => $model,
                    'filename' => $filename
			]);
        } else {          
            if ($ftype > fall) {
				$which = $ftype;
				$filename = $this->fixPrintDocument($model, $which);
			} else { // fall means all types... WE DON'T USE fall ON CREATE ... 
					// but if for some reason we do want fall onCREATE, the following code creates all documents
				$which = fapproval;
				$filename = $this->fixPrintDocument($model, $which);				
				$which = fjournal;
				$filename2 = $this->fixPrintDocument($model, $which);				
/*				$which = fdocument;
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
        
		// Σβήνει όλες τις σχετικές με τον τύπο μετακίνησης εκτυπώσεις...και τα φιλαράκια
		$this->deleteAllPrints($model, $which); 

        if ($which == fapproval) {
			$sameDecisionModels = $model->allSameDecision();
			$all_count = count($sameDecisionModels);	
        } elseif ($which == fjournal) {
			$sameDecisionModels = $model->selectForPayment($model->from, $model->to);
			$all_count = count($sameDecisionModels);
		}
        for ($c = 0; $c < $all_count; $c++) {
            $currentModel = $sameDecisionModels[$c];	
            $set = $this->setPrintDocument($currentModel, $filename, $which);
			if (!$set) {
				throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested transport was not generated.') . $currentModel->id);
			}
		}
		return $filename;
	}

     /**
     * Creates an exported print for the provided model. 
     * 
     * @param Transport $transportModel
     * @param smallint $which selector of file type
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
		} elseif ($which == fdocument) { //ΔΙΑΒΙΒΑΣΤΙΚΟ ΜΕΤΑΚΙΝΗΣΗΣ
				$templatefilename = $transportModel->type0 ? $transportModel->type0->templatefilename3 : null;
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
		
			$templateProcessor->setValue('DECISION_DATE', Yii::$app->formatter->asDate($transportModel->decision_protocol_date));
			$templateProcessor->setValue('DEC_PROT', $transportModel->decision_protocol);
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
			
			$templateProcessor->setValue('PLAC_DATE', Yii::$app->formatter->asDate($transportModel->employee0->serve_decision_date));
			$templateProcessor->setValue('PLACEMENT_NUM', $transportModel->employee0->serve_decision);
		 
			$templateProcessor->setValue('SURNAME', $transportModel->employee0->surname);
			$templateProcessor->setValue('NAME', $transportModel->employee0->name);
			$templateProcessor->setValue('RANK', $transportModel->employee0->rank);
			$templateProcessor->setValue('SPEC', $transportModel->employee0->specialisation0->code); //. ' (' . $transportModel->employee0->specialisation0->name . ')');
			$templateProcessor->setValue('BASE', $transportModel->base);
			$templateProcessor->setValue('SERVICE_SERVE', $transportModel->employee0->serviceServe->name);
			$templateProcessor->setValue('POSITION', $transportModel->employee0->position0->name);

			$sameDecisionModels = $transportModel->allSameDecision();
			$all_count = count($sameDecisionModels);

			//Αν είναι μία ή παραπάνω οι μετακινήσεις που εγκρίνονται μιλάμε στον πληθυντικό
			if ($all_count > 1) {
				$templateProcessor->setValue('APPLIC', 'Τις αιτήσεις');
				$templateProcessor->setValue('TRANS_APP', 'τις μετακινήσεις');
				$templateProcessor->setValue('TRANS_NUM', 'Οι παραπάνω μετακινήσεις');
			} else {
				$templateProcessor->setValue('APPLIC', 'Την αίτηση');
				$templateProcessor->setValue('TRANS_APP', 'τη μετακίνηση');
				$templateProcessor->setValue('TRANS_NUM', 'Η παραπάνω μετακίνηση');       	
			}		
			
			// Fund ids της απόφασης ώστε να τα εμφανίσω μόνο μία φορά...
			$funds = [];
			$fnum = 0;
			$templateProcessor->cloneRow('DATES', $all_count);
			for ($c = 0; $c < $all_count; $c++) {
				$i = $c + 1;
				$currentModel = $sameDecisionModels[$c];	
				if ($currentModel->start_date == $currentModel->end_date) {
					$templateProcessor->setValue('DATES' . "#{$i}", Yii::$app->formatter->asDate($currentModel->start_date));
				} else {
					$templateProcessor->setValue('DATES' . "#{$i}", Yii::$app->formatter->asDate($currentModel->start_date) . '-' . Yii::$app->formatter->asDate($currentModel->end_date));		
				}
				$templateProcessor->setValue('ROUTE' . "#{$i}", $currentModel->fromTo->name);
				$templateProcessor->setValue('MODE' . "#{$i}", $currentModel->mode0->name);
				$templateProcessor->setValue('DAYS' . "#{$i}", $currentModel->days_applied);
				$templateProcessor->setValue('CAUSE' . "#{$i}", $currentModel->reason);

				// Τα fund->id που θα χρησιμοποιήσω
				if ($currentModel->fund1 !== null) {
					$funds[$fnum] = $currentModel->fund1;
					$fnum++;
				}
				if ($currentModel->fund2 !== null) {
					$funds[$fnum] = $currentModel->fund2;
					$fnum++;
				}
				if ($currentModel->fund3 !== null) {
					$funds[$fnum] = $currentModel->fund3;
					$fnum++;
				}
			}
			//Διαχωρισμός διπλοτύπων...
			$funds = array_unique($funds);      

			$fund_str = '';
			$kae_str = '';

			$k = 5; // Αριθμός ΕΧΟΝΤΑΣ ΥΠΟΨΗ του ΠΡΟΤΥΠΟΥ

			$num = count($funds);
			if ($num > 0) {
				$k++;
				if ($num == 1 ) {
					$fund = $k . '. Τη με αριθ. ';
				} else {
					$fund = $k . '. Τις με αριθ. ';
				}
				for ($g = 0; $g < $num; $g++) {
					$fmodel = \app\models\TransportFunds::findone($funds[$g]);
					if ($fund_str == '') {
						$fund_str = $fund . $fmodel->name . ' / ' .   Yii::$app->formatter->asDate($fmodel->date) . ' (ΑΔΑ: ' . $fmodel->ada . ')';
						$kae_str = $fmodel->code . ' (KAE: ' . $fmodel->kae . ')';
					} else {
						$fund_str .= ', ' . $fmodel->name . ' / ' .   Yii::$app->formatter->asDate($fmodel->date) . ' (ΑΔΑ: ' . $fmodel->ada . ')';
						$kae_str .= ', ' . $fmodel->code . ' (KAE: ' . $fmodel->kae . ')';
					}
				}	    
				if ($fund_str !== '') {		
					if ($num == 1 ) {
						$fund_str .= ' απόφαση ανάληψης υποχρέωσης.';
					} else {
						$fund_str .= ' αποφάσεις ανάληψης υποχρέωσης.';
					}
				}
				$templateProcessor->setValue('KAE', $kae_str);      
				$templateProcessor->setValue('FUND1', $fund_str);      
			}
		}


		if ($which == fjournal) { //ΓΙΑ ΗΜΕΡΟΛΟΓΙΟ ΜΕΤΑΚΙΝΗΣΗΣ
			$templateProcessor->setValue('EMP_NAME', $transportModel->employee0->name . ' ' . $transportModel->employee0->surname);
			$templateProcessor->setValue('RANK', $transportModel->employee0->rank);
			$templateProcessor->setValue('CODE', $transportModel->employee0->specialisation0->code . ' (' . $transportModel->employee0->specialisation0->name . ')' );
			$templateProcessor->setValue('BASE', $transportModel->base);
			$templateProcessor->setValue('BASE2', $transportModel->employee0->work_base);
			$templateProcessor->setValue('AFM', $transportModel->employee0->tax_identification_number);
			$templateProcessor->setValue('AM', $transportModel->employee0->identification_number);		
			$templateProcessor->setValue('IBAN', $transportModel->employee0->iban);
			$templateProcessor->setValue('FIN_DIR', Yii::$app->params['finance_sign']);
			$templateProcessor->setValue('FIN_SERV', Yii::$app->params['finance_service']);
			$templateProcessor->setValue('FIN_NAME', Yii::$app->params['fin_director']);      

			// ΔΙΑΣΤΗΜΑΤΑ ΑΠΟ ΜΕΧΡΙ
			$templateProcessor->setValue('D_STA', Yii::$app->formatter->asDate($transportModel->from));
			$templateProcessor->setValue('D_END', Yii::$app->formatter->asDate($transportModel->to));
			
			$sameDecisionModels = $transportModel->selectForPayment($transportModel->from, $transportModel->to);
			$all_count = count($sameDecisionModels);
			
			$S1 = $S2 = $S3 = $S4 = $S5 = $S6 = $S7 = $S8 = $S9 = $S10 = $SDA = $S719 = $S721 = $S722 = 0.00;
			$templateProcessor->cloneRow('START', $all_count);
			for ($c = 0; $c < $all_count; $c++) {
				$i = $c + 1;
				$currentModel = $sameDecisionModels[$c];	
				$templateProcessor->setValue('START' . "#{$i}", Yii::$app->formatter->asDate($currentModel->start_date));		
				$templateProcessor->setValue('END' . "#{$i}", Yii::$app->formatter->asDate($currentModel->end_date));		
				$templateProcessor->setValue('ROUTE' . "#{$i}", $currentModel->fromTo->name);		
				$templateProcessor->setValue('KLM' . "#{$i}", number_format($currentModel->klm, 1 , ',', '')); 
				$S1 += $currentModel->klm;
				$templateProcessor->setValue('MODE' . "#{$i}", $currentModel->mode0->name);
				$templateProcessor->setValue('DAYS' . "#{$i}", $currentModel->days_applied);
				$SDA += $currentModel->days_applied;		
				$templateProcessor->setValue('KLMR' . "#{$i}", number_format($currentModel->klm_reimb, 2 , ',', ''));
				$S2 += $currentModel->klm_reimb;
				$templateProcessor->setValue('TICK' . "#{$i}", number_format($currentModel->ticket_value, 2 , ',', ''));
				$S3 += $currentModel->ticket_value;	
				$templateProcessor->setValue('D_OUT' . "#{$i}", $currentModel->days_out);	
				$S4 += $currentModel->days_out;
				$templateProcessor->setValue('DAYR' . "#{$i}", number_format($currentModel->day_reimb, 2 , ',', ''));
				$S6 += $currentModel->day_reimb;
				$templateProcessor->setValue('REIM' . "#{$i}", number_format($currentModel->night_reimb, 2 , ',', ''));
				$S5 += $currentModel->night_reimb;

//				$S7 += $currentModel->    ;

				$templateProcessor->setValue('C19' . "#{$i}", number_format($currentModel->code719, 2 , ',', ''));
				$S719 += $currentModel->code719;
				$templateProcessor->setValue('C21' . "#{$i}", number_format($currentModel->code721, 2 , ',', ''));
				$S721 += $currentModel->code721;
				$templateProcessor->setValue('C22' . "#{$i}", number_format($currentModel->code722, 2 , ',', ''));
				$S722 += $currentModel->code722;
				$templateProcessor->setValue('TOT' . "#{$i}", number_format($currentModel->reimbursement, 2 , ',', ''));
				$S8 += $currentModel->reimbursement;
				$templateProcessor->setValue('MT' . "#{$i}", number_format($currentModel->mtpy, 2 , ',', ''));
				$S9 += $currentModel->mtpy;
				$templateProcessor->setValue('CLA' . "#{$i}", number_format($currentModel->pay_amount, 2 , ',', ''));
				$S10 += $currentModel->pay_amount;
			}
			$templateProcessor->setValue('S1', number_format($S1, 1 , ',', ''));
			$templateProcessor->setValue('S2', number_format($S2, 2 , ',', ''));
			$templateProcessor->setValue('S3', number_format($S3, 2 , ',', ''));
			$templateProcessor->setValue('S4', $S4);
			$templateProcessor->setValue('S5', number_format($S5, 2 , ',', ''));
			$templateProcessor->setValue('SDA', $SDA);
			$templateProcessor->setValue('S6', number_format($S6, 2 , ',', ''));
			$templateProcessor->setValue('S7', number_format($S7, 2 , ',', ''));
			$templateProcessor->setValue('S719', number_format($S719, 2 , ',', ''));
			$templateProcessor->setValue('S721', number_format($S721, 2 , ',', ''));
			$templateProcessor->setValue('S722', number_format($S722, 2 , ',', ''));
			$templateProcessor->setValue('S8', number_format($S8, 2 , ',', ''));
			$templateProcessor->setValue('S9', number_format($S9, 2 , ',', ''));
			$templateProcessor->setValue('S10', number_format($S10, 2 , ',', ''));
		}			
		
        $templateProcessor->saveAs($exportfilename);
        if (!is_readable($exportfilename)) {
            throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested transport was not generated.'));
        }

        return $exportfilename;
    }
    
    protected function setPrintDocument($transportModel, $filename, $which)
    {
		$new_print_id = 0;
		$printM = TransportPrint::transportPrintID(basename($filename));
		if ($printM !== null) { //Υπάρχει ήδη από σχετιζόμενη μετακίνηση
			$new_print_id = $printM->id; 
		} else {   // Δεν υπάρχει και το δημιουργώ
			$new_print = new TransportPrint();
			$new_print->filename = basename($filename);
			$new_print->doctype = $which;       
			$ins = $new_print->insert();
			if ($ins == true) { // έγινε εισαγωγή στον Transport_Print
				$new_printM = TransportPrint::transportPrintID(basename($filename));
				$new_print_id = $new_printM->id; 
			}
		}
		if ($new_print_id > 0) {
			$new_print2 = new TransportPrintConnection();
			$new_print2->transport = $transportModel->id;
			$new_print2->transport_print = $new_print_id;
			$ins = $new_print2->insert();
		}
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
		$delsuccess = $this->deleteAllPrints($model, fall);
        if ($delsuccess == true) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Deletion completed succesfully.'));          
		} else {
			Yii::$app->session->setFlash('warning', Yii::t('app', 'Deletion did not complete succesfully.'));          
		}
        return $this->redirect(['view', 'id' => $model->id]);
    }

	protected function deleteAllPrints($transportModel, $type)
	{
		$success = true;
		foreach ($transportModel->transportPrintConnections as $printConnection) {
			if ($type == fall) { //Υπάρχει κουμπί Διαγραφή ΟΛΩΝ
				$unlink_filename = $printConnection->transportPrint0->path;
				if (file_exists($unlink_filename)) {
					unlink($unlink_filename);
				}
				$printid = $printConnection->transportPrint0->id;

				//σβήνω κι όλα τα φιλαράκια...
				$samePrintIdConnections = TransportPrintConnection::samePrintId($printid);
				$all_count = count($samePrintIdConnections);
				for ($c = 0; $c < $all_count; $c++) {
					$current = $samePrintIdConnections[$c];	
					$current->delete();
				}

				if ($printConnection->transportPrint0->delete() == false) {
					$success = false;
				}
			} else {
				if ($printConnection->transportPrint0->doctype == $type) {
					$unlink_filename = $printConnection->transportPrint0->path;
					if (file_exists($unlink_filename)) {
						unlink($unlink_filename);
					}
					$printid = $printConnection->transportPrint0->id;

					//σβήνω κι όλα τα φιλαράκια...
					$samePrintIdConnections = TransportPrintConnection::samePrintId($printid);
					$all_count = count($samePrintIdConnections);
					for ($c = 0; $c < $all_count; $c++) {
						$current = $samePrintIdConnections[$c];	
						$current->delete();
					}
					
					if ($printConnection->transportPrint0->delete() == false) {
						$success = false;
					}
				}	
			}			
			
        }
        return $success;
	}

	/* Send email to $email with $filename attached 
	 * @return Integer = number of emails successfully sent 
	 */  
	protected function sendEmail($filename, $emails)
    { 
		$subject = Yii::t('app', 'Transport journal post');
		$txtBody = Yii::$app->params['companyName'] . ' - ' . Yii::t('app', 'Automated transport journal email');
		$htmlBody = '<b>' . Yii::$app->params['companyName'] . ' - ' . Yii::t('app', 'Automated transport journal email') .'</b>';
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
			$logStr = 'Swift_TransportException in transport-journal-email-sending: ';
			$pos = strpos($e, 'Stack trace:');
			if ($pos>0) {
				$logStr .= substr($e,0, $pos);	
			}
			Yii::info($logStr,'transport-journal-email');
		}
		
		return  $num;//num of messages successfully sent
    }

    public function actionEmailjournal($id, $ftype)
    {
        $model = $this->findModel($id);
        if ($model->deleted) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested transport is deleted.'));
        }
        // Σε κάθε μετακίνηση θα υπάρχει το πολύ ένα έγγραφο του τύπου ftype
        if (($prints = $model->transportPrints) != null) {  
			if (count($prints) == 1) {
				if ($prints[0]->doctype == $ftype) {
					$filename = $prints[0]->filename;
					$printid = $prints[0]->id;
				} else {
					throw new NotFoundHttpException(Yii::t('app', 'The requested transport journal was not found.'));
				}
			} elseif (count($prints) == 2) {
				if ($prints[0]->doctype == $ftype) {
					$filename = $prints[0]->filename;
					$printid = $prints[0]->id;
				} elseif ($prints[1]->doctype == $ftype) {
					$filename = $prints[1]->filename;
					$printid = $prints[1]->id;
				} else {
					throw new NotFoundHttpException(Yii::t('app', 'The requested transport journal was not found.'));
				}
			} else {
				throw new NotFoundHttpException(Yii::t('app', 'The requested transport journal was not found.'));
			}
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested transport was not found.'));
		}
        if (!is_readable(TransportPrint::path($filename))) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested transport was not found.') . TransportPrint::path($filename));
        }

		$emails[0] = $model->employee0->email; // το έχω σαν πίνακα ώστε αν θέλω κι άλλα emails να δουλεύουν όλα
		$sendNum = $this->sendEmail(TransportPrint::path($filename), $emails); // θα είναι 0 ή 1 εκτός αν προσθέσω emails...
 		$userName = Yii::$app->user->identity->username;
		$simple_emails = implode(',', array_filter($emails, function($v){ return $v !== null; }));
		
        if ( isset($sendNum) && ($sendNum > 0)) {
			$numUpd = $this->updateEmailSent($emails, $printid);
			$logStr = 'User ' . $userName . ' sent [transport_print_id = ' . $printid . ' ] of transport_id [' . $model->id . ']. To: [' . $simple_emails . ']. Emails sent: [' . $sendNum . ']. Transport_prints updated: [' . $numUpd . ']. Filename: [' . $filename . '].';
			Yii::info($logStr,'transport-journal-email');
			Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully emailed file on {date} to {num} recepients.', ['date' => date('d/m/Y'), 'num' => $sendNum]));
		} else {
			$logStr = 'User ' . $userName . ' tried to send [transport_print_id = ' . $printid . ' ] of transport_id [' . $model->id . ']. To: [' . $simple_emails . ']. Emails sent: [' . $sendNum . ']. Transport_prints updated: [None]. Filename: [' . $filename . '].';	
			Yii::info($logStr,'transport-journal-email');
			Yii::$app->session->setFlash('danger', Yii::t('app', 'The file was not emailed.'));		
		}

        return $this->redirect(['print', 'id' => $id, 'ftype' => $ftype]);
    }

    protected function updateEmailSent($emails, $printid)
    {
		$printModel = TransportPrint::findOne($printid);	
		if ($printModel !== NULL) {
			if (count($emails) > 0) {
				$printModel->to_emails = implode(',', array_filter($emails, function($v){ return $v !== null; }));
			}	
			$printModel->send_ts = date("Y-m-d H:i:s");
			$upd = $printModel->save();
		}		
		return $upd;
	}
    
}
