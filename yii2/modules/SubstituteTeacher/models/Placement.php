<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%stplacement}}".
 *
 * @property integer $id
 * @property integer $teacher_board_id
 * @property integer $call_id
 * @property string $date
 * @property string $decision_board
 * @property string $decision
 * @property string $comments
 * @property integer $altered
 * @property string $altered_at
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Call $call
 * @property TeacherBoard $teacherBoard
 * @property PlacementPosition[] $placementPositions
 */
class Placement extends \yii\db\ActiveRecord
{

    const SCENARIO_UPDATE = 'UPDATE_PLACEMENT';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stplacement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_board_id', 'date'], 'required'],
            [['teacher_board_id', 'call_id'], 'integer'],
            ['teacher_board_id', 'validateTeacherStatus', 'except' => self::SCENARIO_UPDATE],
            [['deleted', 'altered'], 'boolean'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['comments'], 'string'],
            [['decision_board', 'decision'], 'string', 'max' => 500],
            [['call_id'], 'exist', 'skipOnError' => true, 'targetClass' => Call::className(), 'targetAttribute' => ['call_id' => 'id']],
            [['teacher_board_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherBoard::className(), 'targetAttribute' => ['teacher_board_id' => 'id']],
        ];
    }

    public function validateTeacherStatus($attribute, $params, $validator)
    {
        $teacher_board = TeacherBoard::findOne($this->$attribute);
        if (empty($teacher_board)) {
            $this->addError($attribute, Yii::t('substituteteacher', 'The teacher board does not exist.'));
        }

        $teacher = $teacher_board->teacher;
        if (empty($teacher)) {
            $this->addError($attribute, Yii::t('substituteteacher', 'The teacher does not exist.'));
        }

        $teacher_status = $teacher->status;
        if (($teacher_status == Teacher::TEACHER_STATUS_APPOINTED) ||
            ($teacher_status == Teacher::TEACHER_STATUS_NEGATION)) {
            $this->addError($attribute, Yii::t('substituteteacher', 'The teacher status does not allow for placement ({statuslabel}).', ['statuslabel' => Teacher::statusLabel($teacher_status)]));
        }
        // remaining options as TEACHER_STATUS_ELIGIBLE and TEACHER_STATUS_PENDING which shouls be fine
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'teacher_board_id' => Yii::t('substituteteacher', 'Teacher Board ID'),
            'call_id' => Yii::t('substituteteacher', 'Call ID'),
            'date' => Yii::t('substituteteacher', 'Date'),
            'decision_board' => Yii::t('substituteteacher', 'Decision Board'),
            'decision' => Yii::t('substituteteacher', 'Decision'),
            'comments' => Yii::t('substituteteacher', 'Comments'),
            'altered' => Yii::t('substituteteacher', 'Altered'),
            'altered_at' => Yii::t('substituteteacher', 'Altered At'),
            'deleted' => Yii::t('substituteteacher', 'Deleted'),
            'deleted_at' => Yii::t('substituteteacher', 'Deleted At'),
            'created_at' => Yii::t('substituteteacher', 'Created At'),
            'updated_at' => Yii::t('substituteteacher', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()')
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCall()
    {
        return $this->hasOne(Call::className(), ['id' => 'call_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherBoard()
    {
        return $this->hasOne(TeacherBoard::className(), ['id' => 'teacher_board_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPositions()
    {
        return $this->hasMany(PlacementPosition::className(), ['placement_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return PlacementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PlacementQuery(get_called_class());
    }
}
