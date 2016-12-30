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
use yii\data\ActiveDataProvider;

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
						'actions' => ['index', 'view', 'download'],
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
    public function actionIndex($selected = '')
    {
/*      $searchModel = new TransportPrintSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
        
		$searchModel = new TransportPrintSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		if ($selected == '') {
			//Δημιουργώ (άδειο) τον DataProvider
			$query = TransportPrint::find();
			$query->where('0=1');
			$choiceDataProvider = new ActiveDataProvider([
				'query' => $query,
				'pagination' => [ 'pageSize' => 10 ],
			]);	
		} else {
			$query = TransportPrint::find();
			$query->where(' id IN ( ' . $selected . ' ) ' );
			$choiceDataProvider = new ActiveDataProvider([
				'query' => $query,
				'pagination' => [ 'pageSize' => 10 ],
			]);									
		}
		return $this->render('index',[
			'choiceDataProvider' => $choiceDataProvider, 
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'selected' => $selected,
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
	
	public function actionBulk()
	{ // $selected έχει όλα τα Ids του κάτω grid
		$comma_separated = Yii::$app->request->post('selected');
		if ($comma_separated !== '') {
			$selected = explode(',', $comma_separated);
		} else {
			$selected = [];
		}
		if(isset($_POST['createdocs'])) {
			if (count($selected) > 0) {			
				return $this->fixPrintDocument($selected);
			} else {
				Yii::$app->session->setFlash('warning', Yii::t('app', 'Please choose some journals to proceed.'));          
				return $this->redirect(['index']);			
			}			
		} else if(isset($_POST['remove'])) {
			$selection = (array)Yii::$app->request->post('selection');
			$selected = array_diff($selected, $selection); 	
			$count = count($selected);
			if ($count > 0) {
				$comma_separated = implode(",", $selected);
				return $this->redirect(['index', 'selected' => $comma_separated]);
			} else {
				return $this->redirect(['index']);
			}		
		} else {
			return $this->redirect(['index', 'selected' => $comma_separated]);
		}
    }

	public function actionChoose()
	{	
		$comma_separated = Yii::$app->request->post('selected');
		if (isset($_POST['chosen'])) {
			if ($comma_separated !== '') {
				$selected = explode(',', $comma_separated);
			} else {
				$selected = [];
			}
			$selection = (array)Yii::$app->request->post('selection');
			$selection = array_merge($selected, $selection); 	
			$selection = array_unique ($selection);
			$filteredSel = $this->filterSelected($selection, Transport::fjournal);
			$count = count($filteredSel);
			if ($count > 0) {
				$comma_separated = implode(",", $filteredSel);
				$this->redirect(['index', 'selected' => $comma_separated]);
			} else {
				$this->redirect(['index']);
			}		
		} elseif ((isset($_POST['paid']))) {
			$selection = (array)Yii::$app->request->post('selection');
			$filteredSel = $this->filterSelected($selection, Transport::fdocument);
			if (count($filteredSel) > 0) {			
				$filteredSel = array_unique($filteredSel);
				// Mark prints
				$count = count($filteredSel);
				$print_ids = '';
				for ($c = 0; $c < $count; $c++) {
					$currentModel = TransportPrint::Findone($filteredSel[$c]);
					if ($currentModel !== null) {
						$currentModel->paid = true;
						$currentModel->save();
						$print_ids .= ' ' . $currentModel->id;
					}	
				}
				// Mark Transports
				$transports = $this->getTransportsFromPrintId($filteredSel);
				$all_count = count($transports);		
				$transport_ids = '';
				for ($c = 0; $c < $all_count; $c++) {
					$currentModel = $transports[$c];	
					$currentModel->paid = true;
					$currentModel->save();
					$transport_ids .= ' ' . $currentModel->id;
				}	
				$userName = Yii::$app->user->identity->username;
				$logStr = 'User ' . $userName . ' marked as paid prints with ids [' . $print_ids . '] (and automatically transports with ids [' . $transport_ids . ']';
				Yii::info($logStr,'transport');
			} else {
				Yii::$app->session->setFlash('warning', Yii::t('app', 'Please choose some documents or reports to proceed.'));          
			}
			return $this->redirect(['index', 'selected' => $comma_separated]);							
		} elseif ((isset($_POST['unpaid']))) {
			$selection = (array)Yii::$app->request->post('selection');
			$filteredSel = $this->filterSelected($selection, Transport::fdocument);
			if (count($filteredSel) > 0) {
				$filteredSel = array_unique($filteredSel);		
				// Mark prints
				$count = count($filteredSel);
				$print_ids = '';
				for ($c = 0; $c < $count; $c++) {
					$currentModel = TransportPrint::Findone($filteredSel[$c]);
					if ($currentModel !== null) {
						$currentModel->paid = false;
						$currentModel->save();
						$print_ids .= ' ' . $currentModel->id;
					}	
				}
				// Mark Transports		
				$transports = $this->getTransportsFromPrintId($filteredSel);
				$all_count = count($transports);		
				$transport_ids = '';
				for ($c = 0; $c < $all_count; $c++) {
					$currentModel = $transports[$c];	
					$currentModel->paid = false;
					$currentModel->save();
					$transport_ids .= ' ' . $currentModel->id;
				}
				$userName = Yii::$app->user->identity->username;
				$logStr = 'User ' . $userName . ' marked as Unpaid prints with ids [' . $print_ids . '] (and automatically transports with ids [' . $transport_ids . ']';
				Yii::info($logStr,'transport');			
			} else {
				Yii::$app->session->setFlash('warning', Yii::t('app', 'Please choose some documents or reports to proceed.'));          
			}
			return $this->redirect(['index', 'selected' => $comma_separated]);							
		} else {
			return $this->redirect(['index', 'selected' => $comma_separated]);
		}
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
	
	public function actionPrintdata()
    {		
		$request = Yii::$app->request;	
		if ($request->isPost) {
			$post = $request->post();
			$results = [];
			if ($request->post('results0') !== NULL) {			
				$results[0] = $request->post('results0');
			}
			if ($request->post('results1') !== NULL) {			
				$results[1] = $request->post('results1');
			}
			if ($request->post('results2') !== NULL) {			
				$results[2] = $request->post('results2');
			}
			if ($request->post('results3') !== NULL) {			
				$results[3] = $request->post('results3');
			}
			if ($request->post('results4') !== NULL) {			
				$results[4] = $request->post('results4');
			}
			if ($request->post('results5') !== NULL) {			
				$results[5] = $request->post('results5');
			}
			if ($request->post('results6') !== NULL) {			
				$results[6] = $request->post('results6');
			}
			if ($request->post('results7') !== NULL) {			
				$results[7] = $request->post('results7');
			}
			if ($request->post('results8') !== NULL) {			
				$results[8] = $request->post('results8');
			}
			if ($request->post('results9') !== NULL) {			
				$results[9] = $request->post('results9');
			}
			if ($request->post('results10') !== NULL) {			
				$results[10] = $request->post('results10');
			}
			if ($request->post('results11') !== NULL) {			
				$results[11] = $request->post('results11');
			}
			if ($request->post('rep_num') !== NULL) {			
				$rep_num = $request->post('rep_num');
				if ($results[11] !== $rep_num) {
					$results[11] = $rep_num;
				}
			}
			if ($request->post('whole_amount') !== NULL) {			
				$results[12] = $request->post('whole_amount');
			}
			if ($request->post('protocol_date') !== NULL) {			
				$date1 = $request->post('protocol_date');
				$date = str_replace('/', '-', $date1);
				$results[13] = date('Y-m-d', strtotime($date));				
			}
			if ($request->post('protocol') !== NULL) {			
				$results[14] = $request->post('protocol');
			}
			if ($request->post('comma_separated') !== NULL) {			
				$comma_separated = $request->post('comma_separated');
				$filteredSel = explode(',', $comma_separated);
			}
				
			$results[0] = $this->generateCoverDocument($results); //Δημιουργώ και αλλάζω το filename σε exportfilename
			
			$reportfname = $results[1];

			$transports = $this->getTransportsFromPrintId($filteredSel);
			$transportModel = $transports[0];
			$all_count = count($transports);
				
			$which = Transport::fdocument;
			TransportController::deleteAllPrints($transportModel, $which); 	
			for ($c = 0; $c < $all_count; $c++) {
				$currentModel = $transports[$c];	
				$set = $this->setPrintDocument($currentModel, $results, $which); // Εδώ θα στείλω κι άλλα δεδομένα
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
			//Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully generated report.'));          
			Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully generated files on {date}.', ['date' => date('d/m/Y')]));          
			return $this->redirect(['index']);			
		}
    }
	
    public function Coversel($comma_separated, $results, $ftype)
    {   // SUBMIT στην PRINTDATA
		return $this->render('coverdata', ['comma_separated' => $comma_separated, 'results' => $results, 'ftype' => $ftype] );
    }
	
	
	protected function filterSelected($selection, $ftype)
	{
		$filteredSel = []; //Θα πάρω μόνο αυτά που είναι $ftype
		$k = 0;
		foreach ($selection as $id) {
			$trPrint = TransportPrint::findOne((int)$id);
			if (($ftype !== Transport::fdocument) && ($ftype !== Transport::freport)) {
				if ($trPrint->doctype == $ftype) {
					$filteredSel[$k] = $trPrint->id;
					$k++;
				}
			} else { //asked for Report or Document
				if (($trPrint->doctype == Transport::fdocument) || ($trPrint->doctype == Transport::freport)) {
					$filteredSel[$k] = $trPrint->id;
					$k++;
				}		
			}
		}
		return $filteredSel;
	}
	
	/* Generate file
	 * @return Filename	 
	 */
	protected function fixPrintDocument($selection)
	{
		$filteredSel = $this->filterSelected($selection, Transport::fjournal);
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
				$results = $this->generatePrintDocument($transportModel, $transportPrints); //REPORT

				$year = $results[8];
				$maxPrint = TransportPrint::transportNum($year);
				if ($maxPrint !== null) {
					$rep_num =  $maxPrint->report_num + 1;
				} else {
					$rep_num = 1;
				}		
				$results[11] = $rep_num;
				
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
				return $this->Coversel($comma_separated, $results, Transport::fdocument);		
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
				return $this->redirect(['index', 'selected' => $comma_separated]);
			}
		} else {
			Yii::$app->session->setFlash('danger', Yii::t('app', 'You must select journals to proceed. Please correct your selection.'));          		    		
			return $this->redirect(['index']);
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
			$new_print->report_year = $results[8];		
			$new_print->report_num = $results[11];		
			if ($which == Transport::fdocument) {
				$new_print->whole_amount = $results[12];		
				$new_print->report_date = $results[13];		
				$new_print->report_prot = $results[14];		
			}
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
    
	protected function generateCoverDocument($results)
	{
/*		$results[0] 	// document
		$results[1] 	// report
		$results[2] 	// sum code 719
		$results[3] 	// sum code 721
		$results[4] 	// sum code 722
		$results[5] 	// total amount
		$results[6] 	// mtpy
		$results[7] 	// clean amount
		$results[8] 	// year of, may be YYYY or YYYY-YYYY
		$results[9] 	// minDate
		$results[10] 	// maxDate
		$results[11] 	// αύξων αριθμός αποστολής
		$results[12] 	// ποσό ολογράφως			
		$results[13] 	// protocol_date
		$results[14] 	// protocol 	*/

		$dts = date('YmdHis');
		$documentFilename = $results[0];
		$whole_amount = $results[12];
		$rep_date = $results[13];
		$rep_prot = $results[14];
		$rep_num = $results[11];
		$S719 = $results[2];
		$S721 = $results[3];
		$S722 = $results[4];
		$total = $results[5];
		
		//------------------- DOCUMENT ---------------------------------
        $exportfilename1 = Yii::getAlias("@vendor/admapp/exports/transports/{$dts}_{$documentFilename}");
		$documentProcessor = new TemplateProcessor(Yii::getAlias("@vendor/admapp/resources/transports/{$documentFilename}"));       

		$documentProcessor->setValue('DECISION_DATE', Yii::$app->formatter->asDate($rep_date));
		$documentProcessor->setValue('DEC_PROT', $rep_prot);	
		$documentProcessor->setValue('TRANS_PERSON', Yii::$app->params['transportPerson']);
		$documentProcessor->setValue('TRANS_PHONE', Yii::$app->params['transportPhone']);
		$documentProcessor->setValue('TRANS_FAX', Yii::$app->params['transportFax']);
		$documentProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['director_sign']);
		$documentProcessor->setValue('DIRECTOR', Yii::$app->params['director']);      
		//Αν επιλέγεται ο Αναπληρωτής του Περιφερειακού
		//$documentProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['surrogate_sign']);
		//$documentProcessor->setValue('DIRECTOR', Yii::$app->params['surrogate']);      
	
		$documentProcessor->setValue('WHOLE_AMOUNT', $whole_amount);
		$documentProcessor->setValue('DEC_NUM', $rep_num);
		
		$documentProcessor->setValue('AM19', number_format($S719, 2 , ',', ''));
		$documentProcessor->setValue('AM21', number_format($S721, 2 , ',', ''));
		$documentProcessor->setValue('AM22', number_format($S722, 2 , ',', ''));
		$documentProcessor->setValue('NUM_TOTAL', number_format($total, 2 , ',', ''));
        $documentProcessor->saveAs($exportfilename1);

   		$userName = Yii::$app->user->identity->username;
		$logStr = 'User ' . $userName . ' generated transport cover document [' . $exportfilename1 . ']';
		Yii::info($logStr,'transport');			

        if (!is_readable($exportfilename1)) {
            throw new NotFoundHttpException(Yii::t('app', 'The print document for the requested transport was not generated.'));
        }
        //------------------- END DOCUMENT ------------------------------
                
        return $exportfilename1; // document	
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
		$reportFilename = $transportModel->type0 ? $transportModel->type0->templatefilename4 : null;
		$documentFilename = $transportModel->type0 ? $transportModel->type0->templatefilename3 : null;
		if (($reportFilename === null) || ($documentFilename === null)) {
			throw new NotFoundHttpException(Yii::t('app', 'There is no associated template file for this transport type.'));
		}
        $exportfilename2 = Yii::getAlias("@vendor/admapp/exports/transports/{$dts}_{$reportFilename}");

		//------------------- REPORT ---------------------------------

        $reportProcessor = new TemplateProcessor(Yii::getAlias("@vendor/admapp/resources/transports/{$reportFilename}"));
		
		$reportProcessor->setValue('TRANS_PHONE', Yii::$app->params['transportPhone']);
		$reportProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['director_sign']);
		$reportProcessor->setValue('DIRECTOR', Yii::$app->params['director']);      
		$reportProcessor->setValue('MT', (Yii::$app->params['trans_mtpy'] * 100));      
		//Αν επιλέγεται ο Αναπληρωτής του Περιφερειακού
		//$reportProcessor->setValue('DIRECTOR_SIGN', Yii::$app->params['surrogate_sign']);
		//$reportProcessor->setValue('DIRECTOR', Yii::$app->params['surrogate']);      
		
		$all_count = count($transportPrints);
		$S8 = $S9 = $S10 = $S719 = $S721 = $S722 = 0.00;	
		$reportProcessor->cloneRow('AA', $all_count);
		$logprints = '';
		for ($c = 0; $c < $all_count; $c++) {
			$i = $c + 1;
			$currentModel = $transportPrints[$c];
			
			$logprints .= ' ' . $currentModel->id;
			
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

		$userName = Yii::$app->user->identity->username;
		$logStr = 'User ' . $userName . ' generated transport report [' . $exportfilename2 . '] for transport_prints with ids [' . $logprints . ']';
		Yii::info($logStr,'transport');			

        //------------------- END REPORT ------------------------------
      
		$results = [];
		$results[0] = $documentFilename; // document
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
