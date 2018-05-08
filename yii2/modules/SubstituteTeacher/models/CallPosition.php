<?php
namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\modules\SubstituteTeacher\traits\Reference;

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

    use Reference;

    public $available_hours_label; // used to get a human readable one line description of what is offered at this position

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
     * Define fields that should be returned when the model is exposed
     * by or for an API call.
     * 
     * @param array $prefecture_substitutions an array mapping real prefecture id to the index that should be used for the api
     */
    public function toApi($prefecture_substitutions)
    {
        // TODO skip call_id and position_id
        $fields = [
            'label' => $this->position->title,
            'specialty' => $this->position->specialisation->code,
            'school_type' => $this->position->school_type,
            'prefecture' => array_search($this->position->prefecture->id, $prefecture_substitutions), // TODO check if error
            'group' => $this->group, // TODO REMOVE 
            'call_id' => $this->call_id, // TODO REMOVE 
            'position_id' => $this->position_id, // TODO REMOVE 
            'teachers_count' => $this->teachers_count, // TODO REMOVE 
            'hours_count' => $this->hours_count, // TODO REMOVE 
            'ref' => $this->buildSelfReference(['id', 'group'])
        ];

        if ($this->group != 0) {
            // this is part of a group so gather rest of group's information
            $all_group_models = CallPosition::find()
                ->ofCall($this->call_id)
                ->ofGroup($this->group)
                ->joinWith(['position', 'position.prefecture'])
                ->all();
            $titles = [];
            $teachers_count = 0;
            $hours_count = 0;
            $reference_ids = [];
            foreach ($all_group_models as $key => $model) {
                $titles[] = "{$model->position->title} ({$model->available_hours_label})";
                $teachers_count += $model->teachers_count;
                $hours_count += $model->hours_count;
                $reference_ids[] = $model->id;
            }

            $fields['label'] = implode(" & ", $titles);
            $fields['teachers_count'] = $teachers_count; // TODO REMOVE 
            $fields['hours_count'] = $hours_count; // TODO REMOVE 
            $fields['ref'] = $this->buildReference(['id' => $reference_ids, 'group' => $this->group]);
        }

        return $fields;
    }

    public function afterFind()
    {
        parent::afterFind();

        if ($this->hours_count > 0) {
            $this->available_hours_label = "{$this->hours_count} ώρες";
        } else {
            $this->available_hours_label = "ολόκληρο κενό";
        }
    }

    /**
     * Convinience method to get a single position per group (group == 0 is not considered as group)
     * 
     */
    public static function findOnePerGroup($call_id)
    {
        // first, find all that do not belong in a group
        $call_positions = CallPosition::find()
            ->ofCall($call_id)
            ->andWhere(['group' => 0])
            ->joinWith(['call', 'position', 'position.prefecture'])
            ->all();
        // check for groups of positions
        $groups = CallPosition::find()
            ->ofCall($call_id)
            ->andWhere(['<>', 'group', 0])
            ->select(['group'])
            ->distinct()
            ->asArray()
            ->all();
        if (!empty($groups)) {
            // foreach group, get the first position
            foreach ($groups as $group) {
                $first = CallPosition::find()
                    ->ofCall($call_id)
                    ->andWhere(['group' => $group])
                    ->joinWith(['call', 'position', 'position.prefecture'])
                    ->limit(1)
                    ->one();
                if (!empty($first)) {
                    $call_positions[] = $first;
                }
            }
        }

        return $call_positions;
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
