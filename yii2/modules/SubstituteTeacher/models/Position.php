<?php
namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\models\Specialisation;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use spapad\yii2helpers\validators\DefaultOnOtherAttributeValidator;

/**
 * This is the model class for table "{{%stposition}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $operation_id
 * @property integer $specialisation_id
 * @property integer $prefecture_id
 * @property integer $teachers_count
 * @property integer $hours_count
 * @property integer $whole_teacher_hours
 * @property integer $covered_teachers_count
 * @property integer $covered_hours_count
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CallPosition[] $callPositions
 * @property Prefecture $prefecture
 * @property Operation $operation
 * @property Specialisation $specialisation
 */
class Position extends \yii\db\ActiveRecord
{

    public $position_has_type, $position_has_type_label; // use to select teachers or hours count

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return '{{%stposition}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'whole_teacher_hours'], 'required'],
            [['teachers_count', 'covered_teachers_count', 'hours_count', 'covered_hours_count'], 'default', 'value' => 0],
//            [['position_has_type'], DefaultOnOtherAttributeValidator::className(),
//                'if' => '0', 'replace' => true, 'otherAttributeValue' => 0, 'otherAttribute' => 'teachers_count'],
//            [['position_has_type'], DefaultOnOtherAttributeValidator::className(),
//                'if' => '0', 'replace' => true, 'otherAttributeValue' => 0, 'otherAttribute' => 'covered_teachers_count'],
            ['position_has_type', 'safe'],
            [
                ['teachers_count', 'covered_teachers_count'], 'filter',
                'filter' => function ($value) {
                    return 0;
                },
                'when' => function($model) {
                    return $model->position_has_type == 0;
                }
            ],
//            [['position_has_type'], DefaultOnOtherAttributeValidator::className(),
//                'if' => '1', 'replace' => true, 'otherAttributeValue' => 0, 'otherAttribute' => 'hours_count'],
//            [['position_has_type'], DefaultOnOtherAttributeValidator::className(),
//                'if' => '1', 'replace' => true, 'otherAttributeValue' => 0, 'otherAttribute' => 'covered_hours_count'],
            [
                ['hours_count', 'covered_hours_count'], 'filter',
                'filter' => function ($value) {
                    return 0;
                },
                'when' => function($model) {
                    return $model->position_has_type == 1;
                }
            ],
            [['teachers_count', 'covered_teachers_count'], 'required',
                'when' => function($model) {
                    return $model->position_has_type == 1;
                }
            ],
            [['hours_count', 'covered_hours_count'], 'required',
                'when' => function($model) {
                    return $model->position_has_type == 0;
                }
            ],
            [['teachers_count', 'covered_teachers_count', 'hours_count', 'covered_hours_count'], 'integer', 'min' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['operation_id', 'specialisation_id', 'prefecture_id', 'whole_teacher_hours'], 'integer'],
            [['title'], 'string', 'max' => 500],
            [['prefecture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prefecture::className(), 'targetAttribute' => ['prefecture_id' => 'id']],
            [['operation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Operation::className(), 'targetAttribute' => ['operation_id' => 'id']],
            [['specialisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialisation::className(), 'targetAttribute' => ['specialisation_id' => 'id']],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'title' => Yii::t('substituteteacher', 'Title'),
            'operation_id' => Yii::t('substituteteacher', 'Operation'),
            'specialisation_id' => Yii::t('substituteteacher', 'Specialisation'),
            'prefecture_id' => Yii::t('substituteteacher', 'Prefecture'),
            'teachers_count' => Yii::t('substituteteacher', 'Teachers Count'),
            'hours_count' => Yii::t('substituteteacher', 'Hours Count'),
            'whole_teacher_hours' => Yii::t('substituteteacher', 'Whole Teacher Hours'),
            'covered_teachers_count' => Yii::t('substituteteacher', 'Covered Teachers Count'),
            'covered_hours_count' => Yii::t('substituteteacher', 'Covered Hours Count'),
            'created_at' => Yii::t('substituteteacher', 'Created At'),
            'updated_at' => Yii::t('substituteteacher', 'Updated At'),
            'position_has_type' => Yii::t('substituteteacher', 'Position has...'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallPositions()
    {
        return $this->hasMany(CallPosition::className(), ['position_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrefecture()
    {
        return $this->hasOne(Prefecture::className(), ['id' => 'prefecture_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperation()
    {
        return $this->hasOne(Operation::className(), ['id' => 'operation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialisation()
    {
        return $this->hasOne(Specialisation::className(), ['id' => 'specialisation_id']);
    }

    /**
     * @inheritdoc
     * 
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->position_has_type = ($this->teachers_count > 0) ? '1' : '0';
        $this->position_has_type_label = ($this->position_has_type == '1') 
            ? \Yii::t('substituteteacher', 'Teachers')
            : \Yii::t('substituteteacher', 'Hours');
    }

    /**
     * @inheritdoc
     * @return PositionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PositionQuery(get_called_class());
    }
}
