<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use \yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%leave_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $templatefilename
 * @property integer schoolyear_based
 * @property string $create_ts
 * @property string $update_ts
 *
 * @property Leave[] $leaves
 */
class LeaveType extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%leave_type}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_ts',
                'updatedAtAttribute' => 'update_ts',
                'value' => new Expression('NOW()')
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'schoolyear_based'], 'required'],
            [['description'], 'string'],
            [['templatefilename'], 'string', 'max' => 255],
            [['templatefilename'], 'default', 'value' => null],
            [['check'], 'boolean'],
            [['deleted', 'limit', 'reason_num', 'schoolyear_based'], 'integer'],
            [['create_ts', 'update_ts'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'templatefilename' => Yii::t('app', 'Template filename'),
            'limit' => Yii::t('app', 'Limit'),
            'reason_num' => Yii::t('app', 'Reason Number'),
            'check' => Yii::t('app', 'Limit Check'),
            'schoolyear_based' => Yii::t('app', 'Προσμέτρηση άδειας'),
            'create_ts' => Yii::t('app', 'Create Ts'),
            'update_ts' => Yii::t('app', 'Update Ts'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaves()
    {
        return $this->hasMany(Leave::className(), ['type' => 'id']);
    }

    public function getAvailabletemplatefilenames()
    {
        $base_template_dir = Yii::getAlias("@vendor/admapp/resources/");

        $files = FileHelper::findFiles($base_template_dir, ['recursive' => false]);
        if (count($files) > 0) {
            array_walk($files, function (&$item, $key) {
                $item = basename($item);
            });
        }
        sort($files, SORT_STRING);
        return $files;
    }
    
    /**
     * Returns True if the LeaveType is school year based that is it is counted on the school year's duration.
     * Otherwise it returns false.
     * 
     * @param boolean $type
     */
    public static function isSchoolYearBased($type) {        
        return (LeaveType::find()->where(['id' => $type])->one()['schoolyear_based'] == 1);
        
    }

    /**
     * @inheritdoc
     * @return LeaveTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LeaveTypeQuery(get_called_class());
    }
}
