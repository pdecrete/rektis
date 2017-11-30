<?php
namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%stcall}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $application_start
 * @property string $application_end
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CallPosition[] $callPositions
 */
class Call extends \yii\db\ActiveRecord
{

    public $application_start_ts, $application_end_ts;
    public $label;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stcall}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'application_start', 'application_end'], 'required'],
            [['description'], 'string'],
            ['application_start', 'date', 'min' => time(), 'format' => 'php:Y-m-d',
                'timestampAttribute' => 'application_start_ts'],
            ['application_end', 'date', 'min' => time(), 'format' => 'php:Y-m-d',
                'timestampAttribute' => 'application_end_ts'],
            ['application_start_ts', 'compare', 'compareAttribute' => 'application_end_ts', 'operator' => '<', 'enableClientValidation' => false],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 500],
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
            'description' => Yii::t('substituteteacher', 'Description'),
            'application_start' => Yii::t('substituteteacher', 'Application Start'),
            'application_start_ts' => Yii::t('substituteteacher', 'Application Start'),
            'application_end' => Yii::t('substituteteacher', 'Application End'),
            'application_end_ts' => Yii::t('substituteteacher', 'Application End'),
            'created_at' => Yii::t('substituteteacher', 'Created At'),
            'updated_at' => Yii::t('substituteteacher', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallPositions()
    {
        return $this->hasMany(CallPosition::className(), ['call_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->application_start_ts = strtotime($this->application_start);
        $this->application_start = date('Y-m-d', $this->application_start_ts);
        $this->application_end_ts = strtotime($this->application_end);
        $this->application_end = date('Y-m-d', $this->application_end_ts);

        $this->label = $this->title . ', '
            . date('d/m/Y', $this->application_start_ts) . '-'
            . date('d/m/Y', $this->application_end_ts);
    }

    /**
     * Get a list of available choices in the form of
     * ID => LABEL suitable for select lists.
     * 
     * @param int $year
     */
    public static function selectables()
    {
        $choices_aq = (new CallQuery(get_called_class()))
            ->orderBy(['application_start' => SORT_DESC]);

        return ArrayHelper::map($choices_aq->all(), 'id', 'label');
    }

    /**
     * @inheritdoc
     * @return CallQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CallQuery(get_called_class());
    }
}
