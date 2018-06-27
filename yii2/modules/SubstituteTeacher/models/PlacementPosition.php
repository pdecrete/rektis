<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%stplacement_position}}".
 *
 * @property integer $id
 * @property integer $placement_id
 * @property integer $position_id
 * @property integer $teachers_count
 * @property integer $hours_count
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Placement $placement
 * @property Position $position
 */
class PlacementPosition extends \yii\db\ActiveRecord
{
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
            [['placement_id', 'position_id', 'teachers_count', 'hours_count'], 'integer'],
            [['placement_id', 'position_id', 'teachers_count', 'hours_count'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['placement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Placement::className(), 'targetAttribute' => ['placement_id' => 'id']],
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
            'placement_id' => Yii::t('substituteteacher', 'Placement ID'),
            'position_id' => Yii::t('substituteteacher', 'Position ID'),
            'teachers_count' => Yii::t('substituteteacher', 'Teachers Count'),
            'hours_count' => Yii::t('substituteteacher', 'Hours Count'),
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
    public function getPlacement()
    {
        return $this->hasOne(Placement::className(), ['id' => 'placement_id']);
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
