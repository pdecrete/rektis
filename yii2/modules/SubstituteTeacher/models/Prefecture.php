<?php
namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%stprefecture}}".
 *
 * @property integer $id
 * @property string $region
 * @property string $prefecture
 *
 * @property Position[] $positions
 */
class Prefecture extends \yii\db\ActiveRecord
{

    public $label;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stprefecture}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prefecture'], 'required'],
            [['region', 'prefecture'], 'string', 'max' => 150],
            [['prefecture'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'region' => Yii::t('substituteteacher', 'Region'),
            'prefecture' => Yii::t('substituteteacher', 'Prefecture'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPositions()
    {
        return $this->hasMany(Position::className(), ['prefecture_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->label = "{$this->prefecture}, {$this->region}";
    }

    /**
     * Get a list of available choices in the form of
     * ID => LABEL suitable for select lists.
     * 
     * @param null|string $region if provided, filters by region
     */
    public static function selectables($region = null)
    {
        $choices_aq = new PrefectureQuery(get_called_class());
        if ($region !== null) {
            $choices_aq->where(['region' => $region]);
        }
        $choices_aq->orderBy(['region' => SORT_ASC]);

        return ArrayHelper::map($choices_aq->all(), 'id', 'prefecture', 'region');
    }

    /**
     * @inheritdoc
     * @return PrefectureQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PrefectureQuery(get_called_class());
    }
}
