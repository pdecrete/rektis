<?php

namespace app\controllers;

use Yii;
use app\models\TransportPrint;
use app\models\Employee;
use app\models\TransportPrintConnection;
use app\models\Transport;
use app\models\TransportPrintSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \PhpOffice\PhpWord\TemplateProcessor;

/**
 * TransportPrintController implements the CRUD actions for TransportPrint model.
 */
class TransportPrintController extends Controller
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
     * Lists all TransportPrint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransportPrintSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransportPrint model.
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
     * Creates a new TransportPrint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TransportPrint();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TransportPrint model.
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
     * Deletes an existing TransportPrint model.
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
     * Finds the TransportPrint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TransportPrint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransportPrint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
   	public function actionDownload($id)
    {
        $model = $this->findModel($id);
        if ($model != null) {
			$filename = $model->filename;
        } else { // print doesnot exist...
			throw new NotFoundHttpException(Yii::t('app', 'The requested transport file was not found.'));
        }
		if (is_readable(TransportPrint::path($filename))) {
			// all well, send file 
			Yii::$app->response->sendFile(TransportPrint::path($filename));
		} else {
			throw new NotFoundHttpException(Yii::t('app', 'The requested transport file was not found.'));	
		}
    }    
	
	public function actionBulk(){
		$selection = (array)Yii::$app->request->post('selection');

		if (count($selection) > 0) {
			
			
			$this->fixPrintDocument($selection);      
		} else {
			Yii::$app->session->setFlash('warning', Yii::t('app', 'Please choose some journals to proceed.'));          
		}
		return $this->redirect(['index']);			
    }

	protected function getEmployeeFromPrintId($printid) {
		$transportPrintConnections = TransportPrintConnection::Find()
							->where(' transport_print = ' . $printid . ' ' )
							->all();
		$transportPCModel = $transportPrintConnections[0];
		$transportid = $transportPCModel->transport;
		$transportModel = Transport::FindOne($transportid);
		return Employee::FindOne($transportModel->employee);
	}

	protected function getTransportsFromPrintId($printids) {
		$comma_separated = implode(",", $printids);
		$transportPrints = TransportPrintConnection::Find()
							->where(' transport_print IN ( ' . $comma_separated . ' ) ' )
							->all();
		$transportIds = [];
		$k = 0;
		$all_count = count($transportPrints);
		for ($c = 0; $c < $all_count; $c++) {
            $transportPrint = $transportPrints[$c];	
			$transportIds[$k] = $transportPrint->transport;
			$k++;
		}
		$comma_separated = implode(",", $transportIds);
		$transports = Transport::Find()
							->where(' id IN ( ' . $comma_separated . ' ) ' )
							->all();	
		
		return $transports;
	}
	
	/* Generate file
	 * @return Filename	 
	 */
	protected function fixPrintDocument($selection)
	{
		$filteredSel = []; //Θα πάρω μόνο αυτά που είναι Journals
		$k = 0;
		foreach ($selection as $id) {
			$trPrint = TransportPrint::findOne((int)$id);
			// get only journals
			if ($trPrint->doctype == Transport::fjournal) {
				$filteredSel[$k] = $trPrint->id;
				$k++;
			}
		}
		if (count($filteredSel) > 0) {
			$comma_separated = implode(",", $filteredSel);
			$transportPrints = TransportPrint::Find()
								->where(' id IN ( ' . $comma_separated . ' ) ' )
								->all();			

			$transports = $this->getTransportsFromPrintId($filteredSel);
			
			//ΕΛΕΓΧΩ ΑΝ ΚΑΠΟΙΑ ΜΕΤΑΚΙΝΗΣΗ ΕΙΝΑΙ ΚΛΕΙΔΩΜΕΝΗ, ΔΗΛΑΔΗ ΕΧΕΙ ΗΔΗ ΑΠΟΣΤΑΛΕΙ ΣΤΗΝ ΥΔΕ
			$locked = false;
			$lockedids = [];
			$k = 0;
			$all_count = count($transports);	
			for ($c = 0; $c < $all_count; $c++) {
				$currentModel = $transports[$c];	
				if ($currentModel->locked) {
					$locked = true;
					$lockedids[$k] = $currentModel->id;
					$k++;
				}
			}
			
			if (!$locked) {			
				$transportModel = $transports[0]; //send one model for File Types...
				$results = $this->generatePrintDocument($transportModel, $transportPrints);      
				$reportfname = $results[1];

				// Σβήνει όλες τις σχετικές με τον τύπο μετακίνησης εκτυπώσεις...και τα φιλαράκια
				$which = Transport::freport;
				TransportController::deleteAllPrints($transportModel, $which); 	
				for ($c = 0; $c < $all_count; $c++) {
					$currentModel = $transports[$c];	
					$set = $this->setPrintDocument($currentModel, $results, $which);
					if (!$set) {
						throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested transport was not generated.') . $currentModel->id);
					}
				}
				$which = Transport::fdocument;
				TransportController::deleteAllPrints($transportModel, $which); 	
				for ($c = 0; $c < $all_count; $c++) {
					$currentModel = $transports[$c];	
					$set = $this->setPrintDocument($currentModel, $results, $which);
					if (!$set) {
						throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested transport was not generated.') . $currentModel->id);
					}
				}
				
				//Κλειδώνω τα Transports που χρησιμοποιήθηκαν
				for ($c = 0; $c < $all_count; $c++) {
					$currentModel = $transports[$c];	
					$currentModel->locked = True;
					$currentModel->save();
				}
				Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully generated files on {date}.', ['date' => date('d/m/Y')]));          
				return true;
			}	
			else {
				$transStr = '';
				$count = count($lockedids);
				for ($c = 0; $c < $count; $c++) {
					$trans = Transport::FindOne($lockedids[$c]);
					if ($transStr != '') {
						$transStr .= ' && ';
					}
					$transStr .= $trans->employee0->fullname . ' (' .  Yii::$app->formatter->asDate($trans->start_date) . '-' . Yii::$app->formatter->asDate($trans->end_date) . ')';
				}
				$msg = Yii::t('app', 'There are transports already sent. Please correct your data.') . ' ' . $transStr;
				
				Yii::$app->session->setFlash('danger', $msg);          		    		
				return false;
			}
		} else {
			Yii::$app->session->setFlash('danger', Yii::t('app', 'You must select journals to proceed. Please correct your selection.'));          		    		
			return false;	
		}
	}

    protected function setPrintDocument($transportModel, $results, $which)
    {
		if ($which == Transport::fdocument) {	
			$filename = $results[0];
		} elseif ($which == Transport::freport) {
			$filename = $results[1];
		}
		$new_print_id = 0;
		$printM = TransportPrint::transportPrintID(basename($filename));
		if ($printM !== null) { //Υπάρχει ήδη από σχετιζόμενη μετακίνηση
			$new_print_id = $printM->id; 
		} else {   // Δεν υπάρχει και το δημιουργώ
			$new_print = new TransportPrint();
			$new_print->filename = basename($filename);
			$new_print->doctype = $which;
			$new_print->from = $results[9];
			$new_print->to = $results[10];
			$new_print->sum719 = $results[2]; 
			$new_print->sum721 = $results[3]; 
			$new_print->sum722 = $results[4]; 
			$new_print->total = $results[5]; 
			$new_print->sum_mtpy = $results[6]; 
			$new_print->clean = $results[7]; 			
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

     /**
     * Creates two exported prints - A cover document and a report for the selected journals
     * @param TransportModel $transportModel 
     * @param Transports $transports
     * @return Results (DocumentFilename, reportFilename, all economic sums)
     * @throws NotFoundHttpException
     */
    protected function generatePrintDocument($transportModel, $transportPrints)
    {
        $dts = date('YmdHis');
		$documentFilename = $transportModel->type0 ? $transportModel->type0->templatefilename3 : null;
		$reportFilename = $transportModel->type0 ? $transportModel->type0->templatefilename4 : null;
		if (($documentFilename === null) || ($reportFilename === null)) {
			throw new NotFoundHttpException(Yii::t('app', 'There is no associated template file for this transport type.'));
		}
        $exportfilename1 = Yii::getAlias("@vendor/admapp/exports/transports/{$dts}_{$documentFilename}");
        $exportfilename2 = Yii::getAlias("@vendor/admapp/exports/transports/{$dts}_{$reportFilename}");
        $documentProcessor = new TemplateProcessor(Yii::getAlias("@vendor/admapp/resources/transports/{$documentFilename}"));
        $reportProcessor = new TemplateProcessor(Yii::getAlias("@vendor/admapp/resources/transports/{$reportFilename}"));
	
		//------------------- REPORT ---------------------------------
		
		$reportProcessor->setValue('TRANS_PHONE', Yii::$app->params['transportPhone']);
		$reportProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['director_sign']);
		$reportProcessor->setValue('DIRECTOR', Yii::$app->params['director']);      
		//Αν επιλέγεται ο Αναπληρωτής του Περιφερειακού
		//$reportProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['surrogate_sign']);
		//$reportProcessor->setValue('DIRECTOR', Yii::$app->params['surrogate']);      
		
		$all_count = count($transportPrints);
		$S8 = $S9 = $S10 = $S719 = $S721 = $S722 = 0.00;	
		$reportProcessor->cloneRow('AA', $all_count);
		for ($c = 0; $c < $all_count; $c++) {
			$i = $c + 1;
			$currentModel = $transportPrints[$c];
				
			if ($i == 1) { // 1o μοντέλο, αρχικοποίηση
				$minFrom = $currentModel->from;
				$maxTo = $currentModel->to;
			}
			if (($currentModel->from !== null) && ($currentModel->from < $minFrom)) {
				$minFrom = $currentModel->from;		
			}
			if (($currentModel->to !==null) && ($currentModel->to > $maxTo)) {
				$maxTo = $currentModel->to;
			}
			
			$reportProcessor->setValue('AA' . "#{$i}", $i);
			$employee = $this->getEmployeeFromPrintId($currentModel->id);
			$reportProcessor->setValue('NAME' . "#{$i}", $employee->name . ' ' . $employee->surname);

			$reportProcessor->setValue('C719' . "#{$i}", number_format($currentModel->sum719, 2 , ',', ''));
			$S719 += $currentModel->sum719;
			$reportProcessor->setValue('C721' . "#{$i}", number_format($currentModel->sum721, 2 , ',', ''));
			$S721 += $currentModel->sum721;
			$reportProcessor->setValue('C722' . "#{$i}", number_format($currentModel->sum722, 2 , ',', ''));
			$S722 += $currentModel->sum722;
			$reportProcessor->setValue('SUM' . "#{$i}", number_format($currentModel->total, 2 , ',', ''));
			$S8 += $currentModel->total;
			$reportProcessor->setValue('MTPY' . "#{$i}", number_format($currentModel->sum_mtpy, 2 , ',', ''));
			$S9 += $currentModel->sum_mtpy;
			$reportProcessor->setValue('CLA' . "#{$i}", number_format($currentModel->clean, 2 , ',', ''));
			$S10 += $currentModel->clean;
		}
		$reportProcessor->setValue('S719', number_format($S719, 2 , ',', ''));
		$reportProcessor->setValue('S721', number_format($S721, 2 , ',', ''));
		$reportProcessor->setValue('S722', number_format($S722, 2 , ',', ''));
		$reportProcessor->setValue('SSUM', number_format($S8, 2 , ',', ''));
		$reportProcessor->setValue('SMY', number_format($S9, 2 , ',', ''));
		$reportProcessor->setValue('SCLA', number_format($S10, 2 , ',', ''));
		
		$date = date('Y-m-d');
		$parts = explode('-', $minFrom);
		$year1 = $parts[0];
		$parts = explode('-', $maxTo);
		$year2 = $parts[0];
		if ($year1 == $year2) {
			$year = $year1;
		} else {
			$year = $year1 . '-' . $year2;
		}
		
		$reportProcessor->setValue('YEAR', $year);
		$reportProcessor->setValue('DATE', Yii::$app->formatter->asDate($date));
		
        $reportProcessor->saveAs($exportfilename2);
        if (!is_readable($exportfilename2)) {
            throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested transport was not generated.'));
        }
        //------------------- END REPORT ------------------------------
        
		
		//------------------- DOCUMENT ---------------------------------
		
		//---------------------------
		$dec_date = '2016-12-31';
		$dec_prot = 15;
		//---------------------------
	
		$documentProcessor->setValue('DECISION_DATE', Yii::$app->formatter->asDate($dec_date));
		$documentProcessor->setValue('DEC_PROT', $dec_prot);	
		$documentProcessor->setValue('TRANS_PERSON', Yii::$app->params['transportPerson']);
		$documentProcessor->setValue('TRANS_PHONE', Yii::$app->params['transportPhone']);
		$documentProcessor->setValue('TRANS_FAX', Yii::$app->params['transportFax']);
		$documentProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['director_sign']);
		$documentProcessor->setValue('DIRECTOR', Yii::$app->params['director']);      
		//Αν επιλέγεται ο Αναπληρωτής του Περιφερειακού
		//$documentProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['surrogate_sign']);
		//$documentProcessor->setValue('DIRECTOR', Yii::$app->params['surrogate']);      
	
		$documentProcessor->setValue('AM19', number_format($S719, 2 , ',', ''));
		$documentProcessor->setValue('AM21', number_format($S721, 2 , ',', ''));
		$documentProcessor->setValue('AM22', number_format($S722, 2 , ',', ''));
		$documentProcessor->setValue('NUM_TOTAL', number_format($S8, 2 , ',', ''));
        $documentProcessor->saveAs($exportfilename1);
        if (!is_readable($exportfilename1)) {
            throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested transport was not generated.'));
        }
        //------------------- END DOCUMENT ------------------------------
                
		$results = [];
		$results[0] = $exportfilename1; // document
		$results[1] = $exportfilename2; // report
		$results[2] = $S719; //sum code 719
		$results[3] = $S721; //sum code 721
		$results[4] = $S722; //sum code 722
		$results[5] = $S8; //total amount
		$results[6] = $S9;  //mtpy
		$results[7] = $S10; // clean amount
		$results[8] = $year; // year of, may be YYYY or YYYY-YYYY
		$results[9] = $minFrom; // minDate
		$results[10] = $maxTo; // maxDate
		
        return $results;
    }

}
