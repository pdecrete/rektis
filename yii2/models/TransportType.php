<?php

namespace app\models;

use Yii;
use \yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%transport_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $create_ts
 * @property string $update_ts
 * @property integer $deleted
 * @property string $templatefilename1
 * @property string $templatefilename2
 * @property string $templatefilename3
 * @property string $templatefilename4
 *
 * @property Transport[] $transports
 */
class TransportType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transport_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['create_ts', 'update_ts'], 'safe'],
            [['deleted'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['templatefilename1', 'templatefilename2', 'templatefilename3', 'templatefilename4'], 'string', 'max' => 255],
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
            'create_ts' => Yii::t('app', 'Create Ts'),
            'update_ts' => Yii::t('app', 'Update Ts'),
            'deleted' => Yii::t('app', 'Deleted'),
            'templatefilename1' => Yii::t('app', 'Approval filename'),
            'templatefilename2' => Yii::t('app', 'Journal filename'),
            'templatefilename3' => Yii::t('app', 'Document filename'),
            'templatefilename4' => Yii::t('app', 'Report filename'),
        ];
    }

    public function getAvailabletemplatefilenames()
    {
        $base_template_dir = Yii::getAlias("@vendor/admapp/resources/transports/");

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
     * @return \yii\db\ActiveQuery
     */
    public function getTransports()
    {
        return $this->hasMany(Transport::className(), ['type' => 'id']);
    }
}
