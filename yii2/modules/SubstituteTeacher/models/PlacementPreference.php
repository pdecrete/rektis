<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\modules\SubstituteTeacher\traits\Reference;

/**
 * This is the model class for table "{{%stplacement_preference}}".
 *
 * @property integer $id
 * @property integer $teacher_id
 * @property integer $prefecture_id
 * @property integer $school_type
 * @property integer $order
 *
 * @property string $label_for_teacher
 * @property string $school_type_label
 *
 * @property Prefecture $prefecture
 * @property Teacher $teacher
 */
class PlacementPreference extends \yii\db\ActiveRecord
{
    use Reference;

    const SCENARIO_MASS_UPDATE = 'MASS_UPDATE';

    const SCHOOL_TYPE_ANY = 0;
    const SCHOOL_TYPE_SCHOOL = 1;
    const SCHOOL_TYPE_KEDDY = 2;
    const SCHOOL_TYPE_SCHOOL_SYMBOL = 'Σ';
    const SCHOOL_TYPE_KEDDY_SYMBOL = 'Κ';

    public $label_for_teacher;
    public $school_type_label;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stplacement_preference}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'prefecture_id', 'school_type', 'order'], 'integer'],
            [['teacher_id', 'prefecture_id', 'order'], 'required'],
            [['school_type'], 'in', 'range' => [self::SCHOOL_TYPE_SCHOOL, self::SCHOOL_TYPE_KEDDY, self::SCHOOL_TYPE_ANY]],
            [['teacher_id', 'order'], 'unique', 'targetAttribute' => ['teacher_id', 'order'], 'message' => 'The placement preferences order is not unique.',
                'except' => self::SCENARIO_MASS_UPDATE],
            [['prefecture_id', 'teacher_id', 'school_type'], 'unique', 'targetAttribute' => ['teacher_id', 'prefecture_id', 'school_type'], 'message' => 'The combination of Teacher ID, Prefecture ID and School Type has already been taken.'],
            [['prefecture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prefecture::className(), 'targetAttribute' => ['prefecture_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'teacher_id' => Yii::t('substituteteacher', 'Teacher ID'),
            'prefecture_id' => Yii::t('substituteteacher', 'Prefecture ID'),
            'school_type' => Yii::t('substituteteacher', 'School Type'),
            'order' => Yii::t('substituteteacher', 'Order'),
        ];
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
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'teacher_id']);
    }

    /**
     * Get a list of available choices in the form of
     * ID => LABEL suitable for select lists.
     *
     */
    public static function getChoices($for = 'school_type')
    {
        $choices = [];
        if ($for === 'school_type') {
            return [
                (string) self::SCHOOL_TYPE_SCHOOL => Yii::t('substituteteacher', 'School units'),
                (string) self::SCHOOL_TYPE_KEDDY => Yii::t('substituteteacher', 'KEDDY'),
                (string) self::SCHOOL_TYPE_ANY => Yii::t('substituteteacher', 'Any kind of school'),
            ];
        }

        return $choices;
    }

    /**
     * Check ordering of a group of placement preferences.
     * Sets error on first model if ordering is not unique or incremented by 1.
     *
     * @return boolean
     */
    public static function checkOrdering($modelsPlacementPreferences)
    {
        $valid = true;

        $models_cnt = count($modelsPlacementPreferences);
        if ($models_cnt == 0) {
            return $valid;
        }

        $ordering = array_map(function ($m) {
            return $m->order;
        }, $modelsPlacementPreferences);
        if (count(array_unique($ordering)) != $models_cnt) {
            $valid = false;
            $m = reset($modelsPlacementPreferences);
            $m->addError('order', Yii::t('substituteteacher', 'The placement preferences order is not unique.'));
        }
        if (max($ordering) != $models_cnt) {
            $valid = false;
            $m = reset($modelsPlacementPreferences);
            $m->addError('order', Yii::t('substituteteacher', 'The placement preferences order must start at number 1 and increment by one.'));
        }
        return $valid;
    }

    /**
     * Check custom business rules for a group of placement preferences.
     * IGNORES teacher from all calculations.
     *
     * In short:
     * - preferences must not mix prefectures
     * - school type preferences per prefecture may be mixed but ALL, if selected, has to be the only choice in a prefecture
     *
     * Sets error on first model accordingly.
     *
     * @return boolean
     */
    public static function checkRules($modelsPlacementPreferences)
    {
        $valid = true;

        $models_cnt = count($modelsPlacementPreferences);
        if ($models_cnt == 0) {
            return $valid;
        }

        $check_fields = array_map(function ($m) {
            return [
                'order' => $m->order,
                'prefecture_id' => $m->prefecture_id,
                'school_type' => $m->school_type,
                'id' => $m->id
            ];
        }, $modelsPlacementPreferences);
        // sort by order
        usort($check_fields, function ($a, $b) {
            return (int)$a['order'] - (int)$b['order'];
        });
        // get prefectures count
        $prefectures_count = array_count_values(array_map(function ($v) {
            return $v['prefecture_id'];
        }, $check_fields));
        // and check prefectures sequential preference and school type selections
        foreach ($prefectures_count as $prefecture_id => $cnt) {
            if ($cnt == 1) {
                continue; // no need to check sole selections
            }
            $prefecture_placements = array_filter($check_fields, function ($v) use ($prefecture_id) {
                return $v['prefecture_id'] == $prefecture_id;
            });
            $first = reset($prefecture_placements);
            $last = end($prefecture_placements);

            // and check prefectures sequential preference
            if (((int)$last['order'] - (int)$first['order'] + 1) != $cnt) {
                $valid = false;
                $m = reset($modelsPlacementPreferences);
                $m->addError('prefecture_id', Yii::t('substituteteacher', 'The prefectures must not be mixed in ordering.'));
            }
            // check if school type selections are valid; if ALL schools have been selected, it should be the only choice
            $school_type_all_selection = array_filter($prefecture_placements, function ($v) {
                return $v['school_type'] == PlacementPreference::SCHOOL_TYPE_ANY;
            });
            if (!empty($school_type_all_selection)) {
                $valid = false;
                $school_type_all_selection0 = reset($school_type_all_selection);
                $m = array_filter($modelsPlacementPreferences, function ($m) use ($school_type_all_selection0) {
                    return $m->id == $school_type_all_selection0['id'];
                });
                $m0 = reset($m);
                $m0->addError('school_type', Yii::t('substituteteacher', 'When selecting all school types, no other school type selection must be made in the same prefecture.'));
            }
        }

        return $valid;
    }

    public function afterFind()
    {
        parent::afterFind();

        switch ($this->school_type) {
            case self::SCHOOL_TYPE_SCHOOL:
                $this->school_type_label = Yii::t('substituteteacher', 'School units');
                break;
            case self::SCHOOL_TYPE_KEDDY:
                $this->school_type_label = Yii::t('substituteteacher', 'KEDDY');
                break;
            case self::SCHOOL_TYPE_ANY:
                $this->school_type_label = Yii::t('substituteteacher', 'Any kind of school');
                break;
            default:
                $this->school_type_label = Yii::t('substituteteacher', 'Unknown');
                break;
        }

        $this->label_for_teacher = $this->order . ': ' . ($this->prefecture ? $this->prefecture->label : '-')  .
            ', ' . $this->school_type_label;
    }

    /**
     * Define fields that should be returned when the model is exposed
     * by or for an API call.
     */
    public function toApi($prefecture_substitutions, $teacher_substitutions)
    {
        return [
            'teacher_id' => $this->teacher_id,
            // 'teacher' => array_search($this->teacher_id, $teacher_substitutions), // TODO check if error
            'teacher' => array_keys(array_filter($teacher_substitutions, function ($s) {
                return $s == $this->teacher_id;
            })),
            'prefecture_id' => $this->prefecture_id,
            'prefecture' => array_search($this->prefecture_id, $prefecture_substitutions), // TODO check if error
            'school_type' => $this->school_type,
            'order' => $this->order,
            'ref' => $this->buildSelfReference()
        ];
    }

    /**
     * @inheritdoc
     * @return PlacementPreferenceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PlacementPreferenceQuery(get_called_class());
    }
}
