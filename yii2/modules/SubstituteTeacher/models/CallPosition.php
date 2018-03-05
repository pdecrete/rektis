<?php
namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%stcall_position}}".
 *
 * @property integer $id
 * @property integer $group
 * @property integer $call_id
 * @property integer $position_id
 * @property integer $teachers_count
 * @property integer $hours_count
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Call $call
 * @property Position $position
 */
class CallPosition extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stcall_position}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group', 'call_id', 'position_id', 'teachers_count', 'hours_count'], 'integer'],
            [['group', 'teachers_count', 'hours_count'], 'default', 'value' => 0],
            [['group', 'teachers_count', 'hours_count'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            ['position_id', 'unique', 'targetAttribute' => ['call_id', 'position_id']],
            [['call_id'], 'exist', 'skipOnError' => true, 'targetClass' => Call::className(), 'targetAttribute' => ['call_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'id']],
            ['position_id', 'validateOffered', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    public function validateOffered($attribute, $params, $validator)
    {
        if ($this->position) {
            if (($this->teachers_count > $this->position->teachers_count - $this->position->covered_teachers_count) ||
                ($this->hours_count > $this->position->hours_count - $this->position->covered_hours_count)) {
                $this->addError($attribute, Yii::t('substituteteacher', 'Over limits.'));
            }
        } else {
            $this->addError($attribute, Yii::t('substituteteacher', 'Cannot locate the corresponding position.'));
        }
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'group' => Yii::t('substituteteacher', 'Positions Group'),
            'call_id' => Yii::t('substituteteacher', 'Call ID'),
            'position_id' => Yii::t('substituteteacher', 'Position ID'),
            'teachers_count' => Yii::t('substituteteacher', 'Teachers Count'),
            'hours_count' => Yii::t('substituteteacher', 'Hours Count'),
            'created_at' => Yii::t('substituteteacher', 'Created At'),
            'updated_at' => Yii::t('substituteteacher', 'Updated At'),
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
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['id' => 'position_id']);
    }

    /**
     * @inheritdoc
     * @return CallPositionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CallPositionQuery(get_called_class());
    }
}
