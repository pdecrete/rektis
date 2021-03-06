<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\modules\SubstituteTeacher\traits\Selectable;

/**
 * This is the model class for table "{{%stteacher_board}}".
 *
 * @property integer $id
 * @property integer $teacher_id
 * @property integer $specialisation_id
 * @property integer $board_type
 * @property string $points
 * @property integer $order
 * @property integer $status @see Teacher model for STATUS definitions
 *
 * @property Specialisation $specialisation
 * @property PlacementTeacher[] $placementTeachers 
 * @property Applications[] getApplications
 * @property Teacher $teacher
 */
class TeacherBoard extends \yii\db\ActiveRecord
{
    use Selectable;

    const TEACHER_BOARD_TYPE_ANY = 0;
    const TEACHER_BOARD_TYPE_PRIMARY = 1;
    const TEACHER_BOARD_TYPE_SECONDARY = 2;

    public $label, $label_teacher;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stteacher_board}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'specialisation_id', 'status'], 'integer'],
            [['points', 'status'], 'default', 'value' => 0],
            [['order'], 'integer', 'min' => 1],
            [['board_type'], 'default', 'value' => -1],
            [['board_type'], 'in', 'range' => [
                TeacherBoard::TEACHER_BOARD_TYPE_ANY,
                TeacherBoard::TEACHER_BOARD_TYPE_PRIMARY,
                TeacherBoard::TEACHER_BOARD_TYPE_SECONDARY
            ]],
            [['points'], 'number'],
            // this fails after adding with() on main activequery [['teacher_id', 'specialisation_id'], 'unique', 'targetAttribute' => ['teacher_id', 'specialisation_id'], 'message' => 'The combination of Teacher ID and Specialisation ID has already been taken.'],
            ['teacher_id', 'validateUniqueWithSpecialisation'],
            [['specialisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialisation::className(), 'targetAttribute' => ['specialisation_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher_id' => 'id']],
            [['teacher_id', 'specialisation_id', 'status', 'order', 'board_type'], 'filter', 'filter' => 'intval'],
        ];
    }

    public function validateUniqueWithSpecialisation($attribute, $params, $validator)
    {
        $boards = TeacherBoard::find()
            ->andWhere([
                'teacher_id' => $this->$attribute,
                'specialisation_id' => $this->specialisation_id
            ])
            ->andWhere([
                'not', ['id' => $this->id]
            ])
            ->one();

        if (!empty($boards)) {
            $this->addError($attribute, Yii::t('substituteteacher', 'Teacher is already located with this specialisation.'));
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'teacher_id' => Yii::t('substituteteacher', 'Teacher ID'),
            'specialisation_id' => Yii::t('substituteteacher', 'Specialisation'),
            'board_type' => Yii::t('substituteteacher', 'Board Type'),
            'points' => Yii::t('substituteteacher', 'Points'),
            'order' => Yii::t('substituteteacher', 'Order'),
            'year' => Yii::t('substituteteacher', 'Year'),
            'status' => Yii::t('substituteteacher', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialisation()
    {
        return $this->hasOne(Specialisation::className(), ['id' => 'specialisation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherRegistry()
    {
        return $this->hasOne(TeacherRegistry::className(), ['id' => 'registry_id'])
            ->via('teacher');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementTeachers()
    {
        return $this->hasMany(PlacementTeacher::className(), ['teacher_board_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['teacher_board_id' => 'id']);
    }

    public static function boardTypeLabel($board_type)
    {
        switch ($board_type) {
            case TeacherBoard::TEACHER_BOARD_TYPE_ANY:
                $label = Yii::t('substituteteacher', 'Teacher board (any)');
                break;
            case TeacherBoard::TEACHER_BOARD_TYPE_PRIMARY:
                $label = Yii::t('substituteteacher', 'Primary teacher board');
                break;
            case TeacherBoard::TEACHER_BOARD_TYPE_SECONDARY:
                $label = Yii::t('substituteteacher', 'Secondary teacher board');
                break;
            default:
                $label = '-';
                break;
        }
        return $label;
    }

    /**
     * Get a list of available choices in the form of
     * ID => LABEL suitable for select lists.
     *
     */
    public static function getChoices($for = 'board_type')
    {
        $choices = [];
        if ($for === 'board_type') {
            return [
                (string) self::TEACHER_BOARD_TYPE_ANY => self::boardTypeLabel(self::TEACHER_BOARD_TYPE_ANY),
                (string) self::TEACHER_BOARD_TYPE_PRIMARY => self::boardTypeLabel(self::TEACHER_BOARD_TYPE_PRIMARY),
                (string) self::TEACHER_BOARD_TYPE_SECONDARY => self::boardTypeLabel(self::TEACHER_BOARD_TYPE_SECONDARY),
            ];
        }

        return $choices;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->label = '(α/α: ' . $this->order . ') ' .
            TeacherBoard::boardTypeLabel($this->board_type) . ' ' . 
            $this->specialisation->code . ' ' .
            $this->points . ' ' .
            '[' . Teacher::statusLabel($this->status) . ']';
    }

    public static function selectablesWithTeacherInfo()
    {
        return static::selectables('id', function ($data, $default) { return  ' ' . $data->label; }, 'teacher.name', function ($aq) {
            return $aq->orderBy(['teacher_id' => SORT_ASC]);
        });
    }

    /**
     * @inheritdoc
     * @return TeacherBoardQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeacherBoardQuery(get_called_class());
    }
}
