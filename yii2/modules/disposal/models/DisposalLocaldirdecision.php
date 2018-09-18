<?php

namespace app\modules\disposal\models;

use Exception;
use Yii;
use yii\web\User;

/**
 * This is the model class for table "{{%disposal_localdirdecision}}".
 *
 * @property integer $localdirdecision_id
 * @property string $localdirdecision_protocol
 * @property string $localdirdecision_subject
 * @property string $localdirdecision_action
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted
 * @property integer $archived
 *
 * @property DisposalDisposal[] $disposalDisposals
 * @property User $createdBy
 * @property User $updatedBy
 */
class DisposalLocaldirdecision extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disposal_localdirdecision}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['localdirdecision_protocol', 'localdirdecision_subject', 'localdirdecision_action'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted', 'archived'], 'integer'],
            [['localdirdecision_protocol'], 'string', 'max' => 100],
            [['localdirdecision_subject'], 'string', 'max' => 500],
            [['localdirdecision_action'], 'string', 'max' => 200],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'localdirdecision_id' => Yii::t('app', 'Localdirdecision ID'),
            'localdirdecision_protocol' => Yii::t('app', 'Πρωτόκολλο Δ/νσης Σχολείου'),
            'localdirdecision_subject' => Yii::t('app', 'Θέμα Απόφασης Δ/νσης Σχολείου'),
            'localdirdecision_action' => Yii::t('app', 'Πράξη Απόφασης'),
            'created_at' => Yii::t('app', 'Ημ/νία Δημιουργίας'),
            'updated_at' => Yii::t('app', 'Ημ/νία Επεξεργασίας'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted' => Yii::t('app', 'Deleted'),
            'archived' => Yii::t('app', 'Archived'),
        ];
    }

    /*
    public function assignedDisposalsConsistency() {
        $disposal_models = Disposal::find()->where(['localdirdecision_id' => $this->localdirdecision_id])->all();
        if(countcount($disposal_models) == 0)
            return true;
        
        $directorate_id = $disposal_models[0]->getSchool()['directorate_id'];
        foreach ($disposal_models as $disposal_model) {
            if($directorate_id != $disposal_model->getSchool()['directorate_id'])
                return false;
        }
            
        return true;
    }
    
    public function getDirectorate() {
        if(!$this->assignedDisposalsConsistency())
            throw new Exception("Incosistent directorate assignments to disposals");
        $disposal_models = Disposal::find()->where(['localdirdecision_id' => $this->localdirdecision_id])->all();
        return $disposal_models[0]->getSchool()['directorate_id'];
    }
    */
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposals()
    {
        return $this->hasMany(Disposal::className(), ['localdirdecision_id' => 'localdirdecision_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
    

    /**
     * @inheritdoc
     * @return DisposalLocaldirdecisionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DisposalLocaldirdecisionQuery(get_called_class());
    }
}
