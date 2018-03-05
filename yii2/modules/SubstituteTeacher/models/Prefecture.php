<?php
namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\modules\SubstituteTeacher\traits\Selectable;
use app\modules\SubstituteTeacher\traits\Reference;

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
    use Selectable;
    use Reference;

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
            'label' => Yii::t('substituteteacher', 'Prefecture label'),
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
     * Provided a year, get a list of available choices in the form of
     * ID => LABEL suitable for select lists.
     * If year provided is "invalid" all choices are retured.
     *
     * @param int $year
     */
    public static function selectablesForRegion($region = null)
    {
        if ($region !== null) {
            return static::selectables('id', 'prefecture', 'region', function ($aq) use ($year) {
                return $aq->where(['region' => $region])
                    ->orderBy(['region' => SORT_ASC]);
            });
        } else {
            return static::defaultSelectables('id', 'prefecture', 'region');
        }
    }

    public static function defaultSelectables($index_property = 'id', $label_property = 'prefecture', $group_property = 'region')
    {
        return static::selectables($index_property, $label_property, $group_property, function ($aq) {
            return $aq->orderBy(['region' => SORT_ASC]);
        });
    }

    /**
     * Define fields that should be returned when the model is exposed
     * by or for an API call.
     */
    public function toApiJson()
    {
        return [
            'region' => $this->region,
            'prefecture' => $this->prefecture,
            'reference' => $this->buildSelfReference()
        ];
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
