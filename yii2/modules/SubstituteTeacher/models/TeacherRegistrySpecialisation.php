<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\models\Specialisation;

/**
 * This is the model class for table "{{%stteacher_registry_specialisation}}".
 *
 * @property integer $id
 * @property integer $registry_id
 * @property integer $specialisation_id
 *
 * @property Specialisation $specialisation
 * @property TeacherRegistry $registry
 */
class TeacherRegistrySpecialisation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stteacher_registry_specialisation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registry_id', 'specialisation_id'], 'integer'],
            [['registry_id', 'specialisation_id'], 'required'],
            [['registry_id', 'specialisation_id'], 'unique', 'targetAttribute' => ['registry_id', 'specialisation_id'], 'message' => 'The combination of Registry ID and Specialisation ID has already been taken.'],
            [['specialisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialisation::className(), 'targetAttribute' => ['specialisation_id' => 'id']],
            [['registry_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherRegistry::className(), 'targetAttribute' => ['registry_id' => 'id']],
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
            'specialisation_id' => Yii::t('substituteteacher', 'Specialisation ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialisation()
    {
        return $this->hasOne(Specialisation::className(), ['id' => 'specialisation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistry()
    {
        return $this->hasOne(TeacherRegistry::className(), ['id' => 'registry_id']);
    }
}
