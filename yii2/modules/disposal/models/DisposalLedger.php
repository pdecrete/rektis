<?php

namespace app\modules\disposal\models;

use app\models\Teacher;
use app\models\User;
use app\modules\schooltransport\models\Schoolunit;
use Yii;

/**
 * This is the model class for table "{{%disposal_ledger}}".
 *
 * @property integer $ledger_id
 * @property integer $disposal_id
 * @property string $disposal_startdate
 * @property string $disposal_enddate
 * @property integer $disposal_hours
 * @property string $disposal_action
 * @property string $disposal_created_at
 * @property string $disposal_updated_at
 * @property integer $deleted
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $teacher_id
 * @property integer $school_id
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Teacher $teacher
 * @property Schoolunit $school
 */
class DisposalLedger extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disposal_ledger}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disposal_id', 'disposal_startdate', 'disposal_enddate', 'disposal_hours', 'disposal_action', 'teacher_id', 'school_id'], 'required'],
            [['disposal_id', 'disposal_hours', 'deleted', 'created_by', 'updated_by', 'teacher_id', 'school_id'], 'integer'],
            [['disposal_startdate', 'disposal_enddate', 'disposal_created_at', 'disposal_updated_at'], 'safe'],
            [['disposal_action'], 'string', 'max' => 200],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher_id' => 'teacher_id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schoolunit::className(), 'targetAttribute' => ['school_id' => 'school_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ledger_id' => Yii::t('app', 'Ledger ID'),
            'disposal_id' => Yii::t('app', 'Disposal ID'),
            'disposal_startdate' => Yii::t('app', 'Έναρξη Διάθεσης'),
            'disposal_enddate' => Yii::t('app', 'Λήξη Διάθεσης'),
            'disposal_hours' => Yii::t('app', 'Ώρες Διάθεσης'),
            'disposal_action' => Yii::t('app', 'Πράξη Διάθεσης'),
            'disposal_created_at' => Yii::t('app', 'Ημ/νία Δημιουργίας'),
            'disposal_updated_at' => Yii::t('app', 'Ημ/νία Δημιουργίας'),
            'deleted' => Yii::t('app', 'Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'school_id' => Yii::t('app', 'School ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['teacher_id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(Schoolunit::className(), ['school_id' => 'school_id']);
    }

    /**
     * @inheritdoc
     * @return DisposalLedgerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DisposalLedgerQuery(get_called_class());
    }
}
