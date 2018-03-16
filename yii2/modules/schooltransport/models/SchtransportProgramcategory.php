<?php

namespace app\modules\schooltransport\models;

use Yii;

/**
 * This is the model class for table "{{%schtransport_programcategory}}".
 *
 * @property integer $programcategory_id
 * @property string $programcategory_actioncode
 * @property string $programcategory_actiontitle
 * @property string $programcategory_actionsubcateg
 *
 * @property SchtransportProgram[] $schtransportPrograms
 */
class SchtransportProgramcategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%schtransport_programcategory}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['programcategory_actioncode', 'programcategory_actiontitle', 'programcategory_actionsubcateg'], 'required'],
            [['programcategory_actioncode'], 'string', 'max' => 50],
            [['programcategory_actiontitle', 'programcategory_actionsubcateg'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'programcategory_id' => Yii::t('app', 'Programcategory ID'),
            'programcategory_actioncode' => Yii::t('app', 'Κωδικός Δράσης'),
            'programcategory_actiontitle' => Yii::t('app', 'Τίτλος Δράσης'),
            'programcategory_actionsubcateg' => Yii::t('app', 'Κατηγορία Δράσης'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchtransportPrograms()
    {
        return $this->hasMany(SchtransportProgram::className(), ['programcategory_id' => 'programcategory_id']);
    }

    /**
     * @inheritdoc
     * @return SchtransportProgramcategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SchtransportProgramcategoryQuery(get_called_class());
    }
}
