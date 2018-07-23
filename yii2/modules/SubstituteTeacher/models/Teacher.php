<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\modules\SubstituteTeacher\traits\Selectable;
use app\modules\SubstituteTeacher\traits\Reference;

/**
 * This is the model class for table "{{%stteacher}}".
 *
 * @property integer $id
 * @property integer $registry_id
 * @property integer $year
 * @property integer $public_experience
 * @property integer $smeae_keddy_experience
 * @property integer $disability_percentage
 * @property integer $disabled_children
 * @property integer $three_children
 * @property integer $many_children
 *
 * @property string $name
 *
 * @property PlacementPreference[] $placementPreferences
 * @property TeacherRegistry $registry
 * @property TeacherStatusAudit[] $teacherStatusAudits
 * @property Prefecture[] $placementPreferencePrefectures
 * @property TeacherBoard[] $boards
 */
class Teacher extends \yii\db\ActiveRecord
{
    use Selectable;
    use Reference;

    const SCENARIO_CALL_FETCH = 'CALL_FETCH'; // used to specify that model is used in the process of selecting teachers for call

    const TEACHER_STATUS_ELIGIBLE = 0; // can be selected for appointment 
    const TEACHER_STATUS_APPOINTED = 1; // is already appointed 
    const TEACHER_STATUS_NEGATION = 2; // has neglected all appointments 
    const TEACHER_STATUS_PENDING = 3; // is included in an open appointment process 

    public $status, $status_label;
    public $name;
    public $call_use_specialisation_id; // property to hold the specialisation used in a specific call; used in SCENARIO_CALL_FETCH

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stteacher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registry_id', 'year', 'public_experience', 'smeae_keddy_experience', 'disability_percentage', 'disabled_children',
              'three_children', 'many_children'], 'integer'],
            [['registry_id', 'year', 'public_experience', 'smeae_keddy_experience', 'disability_percentage', 'disabled_children',
              'three_children', 'many_children'], 'required'],
            [['year', 'registry_id'], 'unique', 'targetAttribute' => ['year', 'registry_id'], 'message' => 'The combination of Registry ID and Year has already been taken.'],
            [['registry_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherRegistry::className(), 'targetAttribute' => ['registry_id' => 'id']],
            [['call_use_specialisation_id'], 'required', 'on' => self::SCENARIO_CALL_FETCH],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'registry_id' => Yii::t('substituteteacher', 'Registry ID'),
            'year' => Yii::t('substituteteacher', 'Year'),
            'status' => Yii::t('substituteteacher', 'Status'),
            'public_experience' => Yii::t('substituteteacher', 'Public experience'),
            'smeae_keddy_experience' => Yii::t('substituteteacher', 'SMEAE/KEDDY experience'),
            'disability_percentage' => Yii::t('substituteteacher', 'Disability percentage'),
            'disabled_children' => Yii::t('substituteteacher', 'Disabled children'),
            'three_children' => Yii::t('substituteteacher', 'Three children'),
            'many_children' => Yii::t('substituteteacher', 'Many children'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPreferences()
    {
        return $this->hasMany(PlacementPreference::className(), ['teacher_id' => 'id'])
            ->orderBy([PlacementPreference::tableName() . '.[[order]]' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPreferencePrefectures()
    {
        return $this->hasMany(Prefecture::className(), ['id' => 'prefecture_id'])
            ->viaTable('{{%stplacement_preference}}', ['prefecture_id' => 'id'])
            ->from(['prefectures' => '{{%stprefecture}}']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistry()
    {
        return $this->hasOne(TeacherRegistry::className(), ['id' => 'registry_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherStatusAudits()
    {
        return $this->hasMany(TeacherStatusAudit::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBoards()
    {
        return $this->hasMany(TeacherBoard::className(), ['teacher_id' => 'id']);
    }

    /**
     * Get a list of available choices in the form of
     * ID => LABEL suitable for select lists.
     *
     */
    public static function getChoices($for = 'status')
    {
        $choices = [];
        if ($for === 'status') {
            $choices = [
                self::TEACHER_STATUS_ELIGIBLE => Yii::t('substituteteacher', 'Eligible for appointment'),
                self::TEACHER_STATUS_APPOINTED => Yii::t('substituteteacher', 'Teacher appointed'),
                self::TEACHER_STATUS_NEGATION => Yii::t('substituteteacher', 'Teacher denied appointment'),
                self::TEACHER_STATUS_PENDING => Yii::t('substituteteacher', 'Teacher status pending'),
            ];
        } elseif ($for === 'year') {
            // one year before and 2 ahead...
            $year = (int)date('Y');
            $years = range($year - 1, $year + 2);
            $choices = array_combine($years, $years);
        }

        return $choices;
    }

    public static function statusLabel($status) {
        switch ($status) {
            case self::TEACHER_STATUS_ELIGIBLE:
                $status_label = Yii::t('substituteteacher', 'Eligible for appointment');
                break;
            case self::TEACHER_STATUS_APPOINTED:
                $status_label = Yii::t('substituteteacher', 'Teacher appointed');
                break;
            case self::TEACHER_STATUS_NEGATION:
                $status_label = Yii::t('substituteteacher', 'Teacher denied appointment');
                break;
            case self::TEACHER_STATUS_PENDING:
                $status_label = Yii::t('substituteteacher', 'Teacher status pending');
                break;
            default:
                $status_label = null;
                break;
        }
        return $status_label;
    }

    public static function defaultSelectables($index_property = 'id', $label_property = 'name', $group_property = null)
    {
        return static::selectables($index_property, $label_property, $group_property, null);
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->name = ($this->registry ? $this->registry->name : '-') . " ({$this->year})";
        // get the combined status 
        $this->status = self::TEACHER_STATUS_ELIGIBLE; 
        $boards = $this->boards;
        if (empty($boards)) {
            $this->status = self::TEACHER_STATUS_ELIGIBLE; 
        } elseif (count($boards) == 1) {
            $sole_board = reset($boards);
            $this->status = $sole_board->status;
        } else {
            $statuses = array_map(function ($bm) {
                return $bm->status;
            }, $boards);
            if (in_array(self::TEACHER_STATUS_APPOINTED, $statuses)) {
                $this->status = self::TEACHER_STATUS_APPOINTED;
            } elseif (in_array(self::TEACHER_STATUS_PENDING, $statuses)) {
                $this->status = self::TEACHER_STATUS_PENDING;
            } elseif (in_array(self::TEACHER_STATUS_ELIGIBLE, $statuses)) {
                $this->status = self::TEACHER_STATUS_ELIGIBLE;
            } else {
                $this->status = self::TEACHER_STATUS_NEGATION;
            }
        }
        $this->status_label = self::statusLabel($this->status);
    }

    /**
     * Define fields that should be returned when the model is exposed
     * by or for an API call.
     */
    public function toApi()
    {
        // If the model has set the call_use_specialisation_id property, use that one as
        // the specialisation. Otherwise return all specialisations with it.
        $specialty = $specialty_id = null;
        $specialisations = $this->registry->specialisations;
        if ($this->scenario === Teacher::SCENARIO_CALL_FETCH) {
            foreach ($specialisations as $specialisation) {
                if ($specialisation->id == $this->call_use_specialisation_id) {
                    $specialty = $specialisation->code;
                    $specialty_id = $specialisation->id;
                }
            }
        } else {
            $specialty = '-'; // multiple; don't serve frontend...
            $specialty_id = array_map(function ($m) {
                return $m->id;
            }, $specialisations);
        }

        return array_merge(
            [
                'specialty' => $specialty,
                'vat' => $this->registry->tax_identification_number,
                'identity' => $this->registry->identity_number,
                'ref' => $this->buildReference([
                    'id' => $this->id,
                    'specialty_id' => $specialty_id,
                    'firstname' => $this->registry->firstname,
                    'lastname' => $this->registry->surname,
                    'fathername' => $this->registry->fathername,
                    'mothername' => $this->registry->mothername,
                    'email' => $this->registry->email,
                    'mobile_phone' => $this->registry->mobile_phone,
                ])
            ],
            (YII_DEBUG ? [ // only for debugging
                // 'name' => $this->registry->name,
                'specialty_id' => $specialty_id,
                'firstname' => $this->registry->firstname,
                'lastname' => $this->registry->surname,
                'fathername' => $this->registry->fathername,
                'mothername' => $this->registry->mothername,
                'email' => $this->registry->email,
                'mobile_phone' => $this->registry->mobile_phone,
            ] : [
            ])
        );
    }

    /**
     * @inheritdoc
     * @return TeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeacherQuery(get_called_class());
    }
}
