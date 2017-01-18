<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%leave_balance}}".
 *
 * @property integer $id
 * @property integer $employee
 * @property integer $leave_type
 * @property string $year
 * @property integer $days
 *
 * @property Employee $employee0
 * @property LeaveType $leaveType
 */
class LeaveBalance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%leave_balance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee', 'leave_type', 'days'], 'integer'],
            [['year'], 'string', 'max' => 4],
            [['days'], 'integer', 'max' => 25], // Δεν είναι ακριβώς σωστό... θα πρέπει να μπαίνει το όριο του τύπου άδειας...
            [['employee'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee' => 'id']],
            [['leave_type'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveType::className(), 'targetAttribute' => ['leave_type' => 'id']],
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
            'leave_type' => Yii::t('app', 'Leave type'),
            'year' => Yii::t('app', 'Year'),
            'days' => Yii::t('app', 'Days'),
        ];
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
    public function getLeaveType()
    {
        return $this->hasOne(LeaveType::className(), ['id' => 'leave_type']);
    }
    
    /**
     * @return String Leave info str
     */
    public function getInformation()
    {
        return ($this->employee0 ? $this->employee0->fullname : Yii::t('app', 'UNKNOWN'))
                . ' (' . ($this->leaveType ? $this->leaveType->name : Yii::t('app', 'UNKNOWN'))
                . ' )';
    }
    
}
