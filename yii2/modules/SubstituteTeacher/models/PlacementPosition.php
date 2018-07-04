<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%stplacement_position}}".
 *
 * @property integer $id
 * @property integer $placement_teacher_id
 * @property integer $position_id
 * @property integer $teachers_count
 * @property integer $hours_count
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PlacementTeacher $placementTeacher
 * @property Position $position
 */
class PlacementPosition extends \yii\db\ActiveRecord
{

    public $position_label;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stplacement_position}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['placement_teacher_id', 'position_id', 'teachers_count', 'hours_count'], 'integer'],
            [['placement_teacher_id', 'position_id', 'teachers_count', 'hours_count'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['placement_teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlacementTeacher::className(), 'targetAttribute' => ['placement_teacher_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'placement_teacher_id' => Yii::t('substituteteacher', 'Placement Teacher ID'),
            'position_id' => Yii::t('substituteteacher', 'Position ID'),
            'teachers_count' => Yii::t('substituteteacher', 'Teachers Count'),
            'hours_count' => Yii::t('substituteteacher', 'Hours Count'),
            'created_at' => Yii::t('substituteteacher', 'Created At'),
            'updated_at' => Yii::t('substituteteacher', 'Updated At'),
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->position_label = $this->position->title . ' ' . 
            ($this->teachers_count > 0 ? Yii::t('substituteteacher', 'Covered Teachers Count') . ': ' . $this->teachers_count : '' ) .
            ($this->hours_count > 0 ? Yii::t('substituteteacher', 'Covered Hours Count') . ': ' . $this->hours_count : '' ) .
            '';
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
    public function getPlacementTeacher()
    {
        return $this->hasOne(PlacementTeacher::className(), ['id' => 'placement_teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['id' => 'position_id']);
    }

    /**
     * @inheritdoc
     * @return PlacementPositionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PlacementPositionQuery(get_called_class());
    }
}
