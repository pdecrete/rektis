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
            [['name'], 'required'],
            [['description'], 'string'],
            [['templatefilename'], 'string', 'max' => 255],
            [['templatefilename'], 'default', 'value' => null],
            [['check'], 'boolean'],
            [['deleted', 'limit'], 'integer'],
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
            'check' => Yii::t('app', 'Limit Check'),
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
     * @inheritdoc
     * @return LeaveTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LeaveTypeQuery(get_called_class());
    }

}
