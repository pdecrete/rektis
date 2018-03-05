<?php
namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\models\Specialisation;

/**
 * This is the model class for table "{{%stoperation_specialisation}}".
 *
 * @property integer $id
 * @property integer $operation_id
 * @property integer $specialisation_id
 *
 * @property Operation $operation
 * @property Specialisation $specialisation
 */
class OperationSpecialisation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stoperation_specialisation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_id', 'specialisation_id'], 'integer'],
            [['operation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Operation::className(), 'targetAttribute' => ['operation_id' => 'id']],
            [['specialisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialisation::className(), 'targetAttribute' => ['specialisation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'operation_id' => Yii::t('substituteteacher', 'Operation ID'),
            'specialisation_id' => Yii::t('substituteteacher', 'Specialisation ID'),
        ];
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
     * @return OperationSpecialisationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OperationSpecialisationQuery(get_called_class());
    }
}
