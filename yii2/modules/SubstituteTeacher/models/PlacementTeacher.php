<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%stplacement_teacher}}".
 *
 * @property integer $id
 * @property integer $placement_id
 * @property integer $teacher_board_id
 * @property string $comments
 * @property integer $altered
 * @property string $altered_at
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PlacementPosition[] $placementPositions
 * @property Placement $placement
 * @property TeacherBoard $teacherBoard
 */
class PlacementTeacher extends \yii\db\ActiveRecord
{
    const SCENARIO_UPDATE = 'UPDATE_PLACEMENT';

    const PLACEMENT_TEACHER_DELETED = 1;
    const PLACEMENT_TEACHER_NOT_DELETED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stplacement_teacher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['placement_id', 'teacher_board_id'], 'integer'],
            [['altered', 'deleted'], 'boolean'],
            [['teacher_board_id'], 'required'],
            ['teacher_board_id', 'validateTeacherStatus', 'except' => self::SCENARIO_UPDATE],
            [['comments'], 'default', 'value' => ''],
            [['altered_at', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['placement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Placement::className(), 'targetAttribute' => ['placement_id' => 'id']],
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
            'placement_id' => Yii::t('substituteteacher', 'Placement ID'),
            'teacher_board_id' => Yii::t('substituteteacher', 'Teacher Board ID'),
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
    public function getPlacementPositions()
    {
        return $this->hasMany(PlacementPosition::className(), ['placement_teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacement()
    {
        return $this->hasOne(Placement::className(), ['id' => 'placement_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrints()
    {
        return $this->hasMany(PlacementPrint::className(), ['placement_teacher_id' => 'id'])
            ->andOnCondition([PlacementPrint::tableName() . '.[[deleted]]' => PlacementPrint::PRINT_NOT_DELETED]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractPrints()
    {
        return $this->hasMany(PlacementPrint::className(), ['placement_teacher_id' => 'id'])
            ->andOnCondition([
                PlacementPrint::tableName() . '.[[deleted]]' => PlacementPrint::PRINT_NOT_DELETED,
                PlacementPrint::tableName() . '.[[type]]' => PlacementPrint::TYPE_CONTRACT,
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSummaryPrints()
    {
        return $this->hasMany(PlacementPrint::className(), ['placement_teacher_id' => 'id'])
            ->andOnCondition([
                PlacementPrint::tableName() . '.[[deleted]]' => PlacementPrint::PRINT_NOT_DELETED,
                PlacementPrint::tableName() . '.[[type]]' => PlacementPrint::TYPE_SUMMARY,
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherBoard()
    {
        return $this->hasOne(TeacherBoard::className(), ['id' => 'teacher_board_id']);
    }

    /**
     * @inheritdoc
     * @return PlacementTeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PlacementTeacherQuery(get_called_class());
    }
}
