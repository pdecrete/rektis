<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\models\Specialisation;

/**
 * This is the model class for table "{{%stteacher_board}}".
 *
 * @property integer $id
 * @property integer $teacher_id
 * @property integer $specialisation_id
 * @property integer $board_type
 * @property string $points
 * @property integer $order
 *
 * @property Specialisation $specialisation
 * @property Teacher $teacher
 */
class TeacherBoard extends \yii\db\ActiveRecord
{
    const TEACHER_BOARD_TYPE_ANY = 0;
    const TEACHER_BOARD_TYPE_PRIMARY = 1;
    const TEACHER_BOARD_TYPE_SECONDARY = 2;

    public $label;

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
            [['teacher_id', 'specialisation_id'], 'integer'],
            [['order'], 'integer', 'min' => 1],
            [['board_type'], 'in', 'range' => [
                TeacherBoard::TEACHER_BOARD_TYPE_ANY,
                TeacherBoard::TEACHER_BOARD_TYPE_PRIMARY,
                TeacherBoard::TEACHER_BOARD_TYPE_SECONDARY
            ]],
            [['points'], 'default', 'value' => 0],
            [['points'], 'number'],
            [['teacher_id', 'specialisation_id'], 'unique', 'targetAttribute' => ['teacher_id', 'specialisation_id'], 'message' => 'The combination of Teacher ID and Specialisation ID has already been taken.'],
            [['specialisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialisation::className(), 'targetAttribute' => ['specialisation_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'teacher_id' => Yii::t('substituteteacher', 'Teacher ID'),
            'specialisation_id' => Yii::t('substituteteacher', 'Specialisation ID'),
            'board_type' => Yii::t('substituteteacher', 'Board Type'),
            'points' => Yii::t('substituteteacher', 'Points'),
            'order' => Yii::t('substituteteacher', 'Order'),
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
            $this->points;
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
