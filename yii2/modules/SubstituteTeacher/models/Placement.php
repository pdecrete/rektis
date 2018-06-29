<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\modules\SubstituteTeacher\traits\Selectable;

/**
 * This is the model class for table "{{%stplacement}}".
 *
 * @property integer $id
 * @property integer $call_id
 * @property string $date
 * @property string $decision_board
 * @property string $decision
 * @property string $comments
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Call $call
 * @property PlacementTeacher[] $placementTeachers
 */
class Placement extends \yii\db\ActiveRecord
{

    use Selectable;

    public $label;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stplacement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            [['call_id'], 'integer'],
            [['deleted'], 'boolean'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['comments'], 'string'],
            [['decision_board', 'decision'], 'string', 'max' => 500],
            [['call_id'], 'exist', 'skipOnError' => true, 'targetClass' => Call::className(), 'targetAttribute' => ['call_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'call_id' => Yii::t('substituteteacher', 'Call'),
            'date' => Yii::t('substituteteacher', 'Date'),
            'decision_board' => Yii::t('substituteteacher', 'Decision Board'),
            'decision' => Yii::t('substituteteacher', 'Decision'),
            'comments' => Yii::t('substituteteacher', 'Comments'),
            'deleted' => Yii::t('substituteteacher', 'Deleted'),
            'deleted_at' => Yii::t('substituteteacher', 'Deleted At'),
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
     * Get a list of available choices in the form of
     * ID => LABEL suitable for select lists.
     */
    public static function defaultSelectables($index_property = 'id', $label_property = 'label', $group_property = null)
    {
        return static::selectables($index_property, $label_property, $group_property, function ($aq) {
            return $aq->orderBy(['date' => SORT_DESC]);
        });
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->label = Yii::$app->formatter->asDate($this->date) . ' ' . $this->decision_board . ' ' . $this->decision;
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
    public function getPlacementTeachers()
    {
        return $this->hasMany(PlacementTeacher::className(), ['placement_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return PlacementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PlacementQuery(get_called_class());
    }
}
