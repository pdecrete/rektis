<?php

namespace app\models;

use Yii;
use app\models\Transport;

/**
 * This is the model class for table "admapp_transport_print".
 *
 * @property integer $id
 * @property string $filename
 * @property string $create_ts
 * @property string $send_ts
 * @property string $to_emails
 *
 * @property TransportPrintConnections $transportPrintConnections
 */
class TransportPrint extends \yii\db\ActiveRecord
{
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admapp_transport_print';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename'], 'required'],
            [['create_ts', 'send_ts', 'paid'], 'safe'],
            [['filename'], 'string', 'max' => 255],
            [['to_emails'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'filename' => Yii::t('app', 'Filename'),
            'doctype' => Yii::t('app', 'Doctype'),
            'create_ts' => Yii::t('app', 'Create Ts'),
            'send_ts' => Yii::t('app', 'Send Ts'),
            'to_emails' => Yii::t('app', 'To Emails'),
            'from' => Yii::t('app', 'Date From'),
            'to' => Yii::t('app', 'Date To'),
            'sum719' => Yii::t('app', 'Sum 719'),
            'sum721' => Yii::t('app', 'Sum 721'),
            'sum722' => Yii::t('app', 'Sum 722'),
            'sum_mtpy' => Yii::t('app', 'Sum MTPY'),
            'total' => Yii::t('app', 'Total'),
            'clean' => Yii::t('app', 'Clean Amount'),
            'asum719' => Yii::t('app', 'Approved Sum 719'),
            'asum721' => Yii::t('app', 'Approved Sum 721'),
            'asum722' => Yii::t('app', 'Approved Sum 722'),
            'asum_mtpy' => Yii::t('app', 'Approved Sum MTPY'),
            'atotal' => Yii::t('app', 'Approved Total'),
            'aclean' => Yii::t('app', 'Approved Clean Amount'),
            'paid' => Yii::t('app', 'Paid')          
        ];
    }

    /**
     * 
     * @see TransportPrint::path
     * @return String 
     */
    public function getPath()
    {
        return $this->path($this->filename);
    }

    /**
     * 
     * @param String $filename 
     * @return String The full path to the file with filename
     */
    public static function path($filename)
    {
        $fname = basename($filename);
        return Yii::getAlias("@vendor/admapp/exports/transports/{$fname}");
    }
       
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportPrintConnections()
    {
        return $this->hasMany(TransportPrintConnection::className(), ['transport_print' => 'id']);
    }

    public static function transportPrintID($filename)
    {
        return TransportPrint::find()
                        ->where(['filename' => $filename])
                        ->one();
    } 

    public function transportNum($year)
    {
        return TransportPrint::find()
                        ->where(['report_year' => $year])
                        ->andWhere(['doctype' => Transport::fdocument])
                        ->orderBy('report_num DESC')
                        ->one();
    } 
    
    /**
     * @return String TransportPrint info str
     */
    public function getInformation()
    {    	
		$transportsconnections = $this->transportPrintConnections;
		if (count($transportsconnections) > 0 ) {
			$transconn = $transportsconnections[0];
			$trans = Transport::findOne($transconn->transport);
			if (($this->doctype == Transport::fapproval) || ($this->doctype == Transport::fjournal)) {
				return ( $trans->employee0 ? $trans->employee0->fullname : Yii::t('app', 'UNKNOWN'))
						. ' (' . ($trans->type0 ? $trans->type0->name : Yii::t('app', 'UNKNOWN')) . ') ';
			} else {
				return ( ($trans->type0 ? $trans->type0->name : Yii::t('app', 'UNKNOWN')) . ' (' . $this->report_num . ' - ' . $this->report_year . ')');
			}
		} else {
			return Yii::t('app', 'UNKNOWN');	
		}
    } 
    
    /**
    * @return String TransportPrint info str
    */
    public function getDocname()
    {    	
	//	$name = 'doctype = ' . $this->doctype;
		switch ($this->doctype) {
			case Transport::fapproval:
				$name = Yii::t('app', 'Approval');
				break;
			case Transport::fjournal:
				$name = Yii::t('app', 'Journal');
				break;
			case Transport::fdocument:
				$name = Yii::t('app', 'Cover');
				break;
			case Transport::freport:
				$name = Yii::t('app', 'Report');
				break;
			default:
				$name = Yii::t('app', '	UNKNOWN');
				break;
		} 
		return $name;
    } 

    public function getpaidname()
    {    	
		switch ($this->paid) {
			case False:
				$name = Yii::t('app', 'No');
				break;
			case True:
				$name = Yii::t('app', 'Yes');
				break;
			default:
				$name = Yii::t('app', '	UNKNOWN');
				break;
		} 
		return $name;
    } 

}
