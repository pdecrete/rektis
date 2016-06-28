<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%leave_print}}".
 *
 * @property integer $id
 * @property integer $leave
 * @property string $filename
 * @property string $create_ts
 *
 * @property Leave $leave0
 */
class LeavePrint extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%leave_print}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['leave'], 'integer'],
            [['filename'], 'required'],
            [['create_ts'], 'safe'],
            [['filename'], 'string', 'max' => 255],
            [['filename'], 'unique'],
            [['leave'], 'exist', 'skipOnError' => true, 'targetClass' => Leave::className(), 'targetAttribute' => ['leave' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'leave' => Yii::t('app', 'Leave'),
            'filename' => Yii::t('app', 'Filename'),
            'create_ts' => Yii::t('app', 'Create Ts'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveObj()
    {
        return $this->hasOne(Leave::className(), ['id' => 'leave']);
    }

    /**
     * 
     * @see LeavePrint::path
     * @return String 
     */
    public function getPath()
    {
        return $this->path($this->filename);
    }

    /**
     * 
     * @param String $filename 
     * @return String The full path to the file with filename
     */
    public static function path($filename)
    {
        $fname = basename($filename);
        return Yii::getAlias("@vendor/admapp/exports/{$fname}");
    }

}
