<?php
namespace app\models;

use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%announcement}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted
 */
class Announcement extends \yii\db\ActiveRecord
{

    const ANNOUNCEMENT_ACTIVE = 0;
    const ANNOUNCEMENT_DELETED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%announcement}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
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
            [['title', 'body'], 'required'],
            [['body'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['deleted'], 'integer'],
            [['title'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'body' => Yii::t('app', 'Body'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted' => Yii::t('app', 'Deleted'),
        ];
    }

    /**
     * @inheritdoc
     * @return AnnouncementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AnnouncementQuery(get_called_class());
    }
}
