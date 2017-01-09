<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admapp_transport".
 *
 * @property integer $id
 * @property integer $employee
 * @property integer $type
 * @property integer $type_journal
 * @property integer $decision_protocol
 * @property string $decision_protocol_date
 * @property integer $application_protocol
 * @property string $application_protocol_date
 * @property string $application_date
 * @property string $accompanying_document
 * @property string $start_date
 * @property string $end_date
 * @property string $reason
 * @property integer $from_to
 * @property string $base
 * @property integer $days_applied
 * @property double $klm
 * @property integer $mode
 * @property double $ticket_value
 * @property double $klm_reimb
 * @property double $night_reimb
 * @property smallint $nights_out
 * @property double $days_out
 * @property double $day_reimb
 * @property double $reimbursement
 * @property double $mpty
 * @property double $pay_amount
 * @property double $code719
 * @property double $code721
 * @property double $code722
 * @property boolean $count_flag
 * @property string $expense_details
 * @property string $comment
 * @property string $create_ts
 * @property string $update_ts
 * @property integer $deleted
 *
 * @property TransportMode $mode0
 * @property TransportDistance $fromTo
 * @property Employee $employee0
 * @property TransportType $type0
 * @property Fund1 $transportFund1
 * @property Fund2 $transportFund2
 * @property Fund3 $transportFund3
 * @property TransportPrintConnection[] $transportPrintConnections
 */

/*
define ('fall', '0'); // τύπος αρχείου για εκτύπωση: ΟΛΑ
define ('fapproval', '1'); // τύπος αρχείου για εκτύπωση: ΕΓΚΡΙΣΗ ΜΕΤΑΚΙΝΗΣΗΣ
define ('fjournal', '2'); // τύπος αρχείου για εκτύπωση: ΗΜΕΡΟΛΟΓΙΟ ΜΕΤΑΚΙΝΗΣΗΣ
define ('fdocument', '3'); // τύπος αρχείου για εκτύπωση: ΔΙΑΒΙΒΑΣΤΙΚΟ ΜΕΤΑΚΙΝΗΣΗΣ
define ('freport', '4'); // τύπος αρχείου για εκτύπωση: ΣΥΓΚΕΝΤΡΩΤΙΚΗ ΚΑΤΑΣΤΑΣΗ ΜΕΤΑΚΙΝΗΣΗΣ
*/

class Transport extends \yii\db\ActiveRecord
{
	public $from; //date('d/m/Y')
	public $to;

	const fall = 0; 	// τύπος αρχείου για εκτύπωση: ΟΛΑ
	const fapproval = 1;// τύπος αρχείου για εκτύπωση: ΕΓΚΡΙΣΗ ΜΕΤΑΚΙΝΗΣΗΣ
	const fjournal = 2;	// τύπος αρχείου για εκτύπωση: ΗΜΕΡΟΛΟΓΙΟ ΜΕΤΑΚΙΝΗΣΗΣ
	const fdocument = 3;// τύπος αρχείου για εκτύπωση: ΔΙΑΒΙΒΑΣΤΙΚΟ ΜΕΤΑΚΙΝΗΣΗΣ
	const freport = 4;	// τύπος αρχείου για εκτύπωση: ΣΥΓΚΕΝΤΡΩΤΙΚΗ ΚΑΤΑΣΤΑΣΗ ΜΕΤΑΚΙΝΗΣΗΣ

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admapp_transport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee', 'type', 'decision_protocol', 'application_protocol', 'from_to', 'days_applied', 'days_out', 'mode', 'deleted', 'fund1', 'fund2', 'fund3', 'nights_out' ], 'integer'],
            [[ 'employee', 'start_date', 'end_date', 'reason', 'from_to', 'mode', 'days_applied', 'ticket_value'], 'required'],
            [['from', 'to', 'decision_protocol_date', 'application_protocol_date', 'application_date', 'start_date', 'end_date', 'create_ts', 'update_ts'], 'safe'],
            [['ticket_value', 'klm_reimb', 'day_reimb', 'night_reimb', 'klm', 'reimbursement', 'mtpy', 'pay_amount', 'code719', 'code721', 'code722'], 'number'],
            [['comment'], 'string'],           
            [['count_flag'], 'boolean'],
            [['accompanying_document', 'base'], 'string', 'max' => 100],
            [['reason'], 'string', 'max' => 200],
            [['expense_details'], 'string', 'max' => 255],
            [['mode'], 'exist', 'skipOnError' => true, 'targetClass' => TransportMode::className(), 'targetAttribute' => ['mode' => 'id']],
            [['from_to'], 'exist', 'skipOnError' => true, 'targetClass' => TransportDistance::className(), 'targetAttribute' => ['from_to' => 'id']],
            [['employee'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee' => 'id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => TransportType::className(), 'targetAttribute' => ['type' => 'id']],
            [['fund1'], 'exist', 'skipOnError' => true, 'targetClass' => TransportFunds::className(), 'targetAttribute' => ['fund1' => 'id']],
            [['fund2'], 'exist', 'skipOnError' => true, 'targetClass' => TransportFunds::className(), 'targetAttribute' => ['fund2' => 'id']],
            [['fund3'], 'exist', 'skipOnError' => true, 'targetClass' => TransportFunds::className(), 'targetAttribute' => ['fund3' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'employee' => Yii::t('app', 'Employee'),
            'type' => Yii::t('app', 'Type Approval'),
            'decision_protocol' => Yii::t('app', 'Decision protocol'),
            'decision_protocol_date' => Yii::t('app', 'Decision date'),
            'application_protocol' => Yii::t('app', 'Application protocol'),
            'application_protocol_date' => Yii::t('app', 'Protocol date'),
            'application_date' => Yii::t('app', 'Application date'),
            'accompanying_document' => Yii::t('app', 'Accompanying documents'),
            'start_date' => Yii::t('app', 'Start date'),
            'end_date' => Yii::t('app', 'End date'),
            'reason' => Yii::t('app', 'Reason'),
            'from_to' => Yii::t('app', 'From To'),
            'base' => Yii::t('app', 'Base'),
            'days_applied' => Yii::t('app', 'Days Applied'),
            'klm' => Yii::t('app', 'Klm'),
            'mode' => Yii::t('app', 'Mode'),
            'ticket_value' => Yii::t('app', 'Ticket Value'),
            'klm_reimb' => Yii::t('app', 'Klm Reimb'),
            'night_reimb' => Yii::t('app', 'Night Reimb'),
            'days_out' => Yii::t('app', 'Days Out'),
			'nights_out' => Yii::t('app', 'Nights Out'),
            'day_reimb' => Yii::t('app', 'Day Reimb'),
            'reimbursement' => Yii::t('app', 'Reimbursement'),
            'mtpy' => Yii::t('app', 'Mtpy'),
            'pay_amount' => Yii::t('app', 'Pay Amount'),
            'code719' => Yii::t('app', 'KAE 719 Amount'),
            'code721' => Yii::t('app', 'KAE 721 Amount'),
            'code722' => Yii::t('app', 'KAE 722 Amount'),
            'count_flag' => Yii::t('app', 'Service count'),
            'fund1' => Yii::t('app', 'Fund1'),
            'fund2' => Yii::t('app', 'Fund2'),
            'fund3' => Yii::t('app', 'Fund3'),
            'expense_details' => Yii::t('app', 'Expense Details'),
            'comment' => Yii::t('app', 'Comments'),
            'create_ts' => Yii::t('app', 'Create Ts'),
            'update_ts' => Yii::t('app', 'Update Ts'),
            'deleted' => Yii::t('app', 'Deleted'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMode0()
    {
        return $this->hasOne(TransportMode::className(), ['id' => 'mode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromTo()
    {
        return $this->hasOne(TransportDistance::className(), ['id' => 'from_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee0()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(TransportType::className(), ['id' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportFund1()
    {
        return $this->hasOne(TransportFunds::className(), ['id' => 'fund1']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportFund2()
    {
        return $this->hasOne(TransportFunds::className(), ['id' => 'fund2']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportFund3()
    {
        return $this->hasOne(TransportFunds::className(), ['id' => 'fund3']);
    }

    public function allSameDecision()
    {
        return Transport::find()
                        ->where([
                            'decision_protocol' => $this->decision_protocol,
                            'decision_protocol_date' => $this->decision_protocol_date,
                            'type' => $this->type,
                            'employee' => $this->employee, 
                            'deleted' => 0
                        ])
                        ->orderBy('id')
                        ->all();
    }

    public function selectForPayment($from, $to)
    {
        return Transport::find()
                        ->where(['type' => $this->type, 'employee' => $this->employee])
                        ->andWhere(['between', 'start_date', $from, $to])
                        ->andWhere(['deleted' => 0])
                        ->orderBy('id')
                        ->all();
    }

    public function lastTransport($employee_id)
    {
        return Transport::find()
                        ->where(['employee' => $employee_id])
                        ->andWhere(['deleted' => 0])
                        ->orderBy('start_date DESC')
                        ->one();
    }

    /**
     * @return String Transport info str
     */
    public function getInformation()
    {
        return ($this->employee0 ? $this->employee0->fullname : Yii::t('app', 'UNKNOWN'))
                . ' (' . ($this->type0 ? $this->type0->name : Yii::t('app', 'UNKNOWN'))
                . ') ' . Yii::$app->formatter->asDate($this->start_date)
                . '-' . Yii::$app->formatter->asDate($this->end_date)
                . '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportPrintConnections()
    {
        return $this->hasMany(TransportPrintConnection::className(), ['transport' => 'id']);
    }  
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportPrints()
    {
        return $this->hasMany(TransportPrint::className(), ['id' => 'transport_print'])
					->viaTable('admapp_transport_print_connection', ['transport' => 'id']);
    }   
}
