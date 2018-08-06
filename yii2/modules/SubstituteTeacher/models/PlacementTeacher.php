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
 * @property integer $dismissed
 * @property string $dismissed_at
 * @property integer $cancelled
 * @property string $cancelled_at
 * @property string $contract_start_date
 * @property string $contract_end_date
 * @property string $service_start_date
 * @property string $service_end_date
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

    public $status_label; 

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
            [['dismissed_ada', 'cancelled_ada'], 'string', 'max' => 200],
            [['dismissed_ada', 'cancelled_ada'], 'match', 'pattern' => \Yii::$app->getModule('SubstituteTeacher')->params['ada-validate-pattern']],
            [['altered', 'dismissed', 'cancelled'], 'boolean'],
            [['altered', 'dismissed', 'cancelled'], 'filter', 'filter' => 'intval'],
            [['teacher_board_id'], 'required'],
            ['teacher_board_id', 'validateTeacherStatus', 'except' => self::SCENARIO_UPDATE],
            [['comments'], 'default', 'value' => ''],
            [['altered_at', 'dismissed_at', 'cancelled_at', 'created_at', 'updated_at'], 'safe'],
            [['placement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Placement::className(), 'targetAttribute' => ['placement_id' => 'id']],
            [['teacher_board_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherBoard::className(), 'targetAttribute' => ['teacher_board_id' => 'id']],
            [['contract_start_date', 'contract_end_date', 'service_start_date', 'service_end_date'], 'date', 'format' => 'php:Y-m-d'],
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
            'dismissed' => Yii::t('substituteteacher', 'Dismissed'),
            'dismissed_at' => Yii::t('substituteteacher', 'Dismissed At'),
            'dismissed_ada' => Yii::t('substituteteacher', 'Dismissed ADA'),
            'cancelled' => Yii::t('substituteteacher', 'Cancelled'),
            'cancelled_at' => Yii::t('substituteteacher', 'Cancelled At'),
            'cancelled_ada' => Yii::t('substituteteacher', 'Cancelled ADA'),
            'contract_start_date' => Yii::t('substituteteacher', 'Contract start date'),
            'contract_end_date' => Yii::t('substituteteacher', 'Contract end date'),
            'service_start_date' => Yii::t('substituteteacher', 'Service start date'),
            'service_end_date' => Yii::t('substituteteacher', 'Service end date'),
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

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $dirty_attributes = $this->getDirtyAttributes();
        $dirty_attributes_names = array_keys($dirty_attributes);
        if (in_array('altered', $dirty_attributes_names)) {
            $this->altered_at = new Expression('NOW()');
        }
        if (in_array('cancelled', $dirty_attributes_names)) {
            $this->cancelled_at = new Expression('NOW()');
        }
        if (in_array('dismissed', $dirty_attributes_names)) {
            $this->dismissed_at = new Expression('NOW()');
        }

        return true;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->status_label = '' 
            . ($this->altered ? 'altered ' : '')
            . ($this->cancelled ? 'cancelled ' : '')
            . ($this->dismissed ? 'dismissed ' : '');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPositions()
    {
        return $this->hasMany(PlacementPosition::className(), ['placement_teacher_id' => 'id'])
            ->orderBy([
                PlacementPosition::tableName() . '.[[hours_count]]' => SORT_DESC,
                PlacementPosition::tableName() . '.[[teachers_count]]' => SORT_DESC,
            ]);
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
