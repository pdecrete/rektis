<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%stapplication}}".
 *
 * @property integer $id
 * @property integer $call_id
 * @property integer $teacher_board_id
 * @property integer $agreed_terms_ts
 * @property integer $state
 * @property integer $state_ts
 * @property string $reference
 * @property string $created_at
 * @property string $updated_at
 * @property integer $deleted
 *
 * @property Call $call
 * @property TeacherBoard $teacherBoard
 * @property ApplicationPosition[] $applicationPositions
 */
class Application extends \yii\db\ActiveRecord
{
    const APPLICATION_DELETED = 1;
    const APPLICATION_NOT_DELETED = 0;

    const STATE_DID_NOT_DENY = 0;
    const STATE_DENIED_TO_APPLY = 1;

    public $state_label;
    public $reference_data; // array holding reference information 

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stapplication}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['call_id', 'teacher_board_id', 'agreed_terms_ts', 'state', 'state_ts', 'deleted'], 'integer'],
            ['deleted', 'default', 'value' => Application::APPLICATION_NOT_DELETED],
            [['reference'], 'default', 'value' => '{}'],
            [['reference'], 'required'],
            [['reference'], 'string'],
            ['state', 'in', 'range' => [self::STATE_DID_NOT_DENY, self::STATE_DENIED_TO_APPLY]],
            [['created_at', 'updated_at'], 'safe'],
            [['call_id'], 'exist', 'skipOnError' => true, 'targetClass' => Call::className(), 'targetAttribute' => ['call_id' => 'id']],
            [['teacher_board_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherBoard::className(), 'targetAttribute' => ['teacher_board_id' => 'id']],
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
            'id' => Yii::t('substituteteacher', 'Application identification'),
            'call_id' => Yii::t('substituteteacher', 'Call identification'),
            'teacher_board_id' => Yii::t('substituteteacher', 'Teacher board identification'),
            'agreed_terms_ts' => Yii::t('substituteteacher', 'Agreed Terms Ts'),
            'state' => Yii::t('substituteteacher', 'State'),
            'state_ts' => Yii::t('substituteteacher', 'State Ts'),
            'reference' => Yii::t('substituteteacher', 'Reference'),
            'created_at' => Yii::t('substituteteacher', 'Created At'),
            'updated_at' => Yii::t('substituteteacher', 'Updated At'),
            'deleted' => Yii::t('substituteteacher', 'Deleted'),
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
    public function getTeacherBoard()
    {
        return $this->hasOne(TeacherBoard::className(), ['id' => 'teacher_board_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationPositions()
    {
        return $this->hasMany(ApplicationPosition::className(), ['application_id' => 'id'])
            ->orderBy(['order' => SORT_ASC]);
    }

    public function afterFind()
    {
        parent::afterFind();

        if (!empty($this->reference)) {
            // the following may thorw an exception, but better let it bubble up
            $this->reference_data = Json::decode($this->reference, true);
        } else {
            $this->reference_data = [];
        }

        if ($this->state == self::STATE_DENIED_TO_APPLY) {
            $this->state_label = '<span class="label label-danger">' . Yii::t('substituteteacher', 'Denied on {d}', ['d' => Yii::$app->formatter->asDatetime($this->state_ts)]) . '</span>';
        } else if (array_key_exists('application_choices', $this->reference_data)) {
            $display_class = ($this->reference_data['application_choices'] > 0) ? 'success' : 'warning';
            $this->state_label = "<span class=\"label label-{$display_class}\">" . Yii::t('substituteteacher', 'Submitted {d} choices', ['d' => $this->reference_data['application_choices']]) . '</span>';
        } else {
            $this->state_label = '';
        }
    }

    /**
     * @inheritdoc
     * @return ApplicationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ApplicationQuery(get_called_class());
    }
}
