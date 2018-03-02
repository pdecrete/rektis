<?php
namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%stfile}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $original_filename
 * @property string $mime
 * @property integer $size
 * @property string $filename
 * @property string $created_at
 * @property string $updated_at
 * @property integer $deleted
 */
class SubstituteTeacherFile extends \yii\db\ActiveRecord
{
    const SCENARIO_UPLOAD_FILE = 'UPLOAD_FILE';
    const FILE_ACTIVE = 0;
    const FILE_DELETED = 1;

    public $uploadfile;
    public $deleted_str;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stfile}}';
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
            [['title', 'original_filename', 'mime', 'filename'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['size', 'deleted'], 'integer'],
            [['title', 'original_filename', 'filename'], 'string', 'max' => 500],
            [['mime'], 'string', 'max' => 90],
            [['uploadfile'], 'required', 'on' => self::SCENARIO_UPLOAD_FILE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'title' => Yii::t('substituteteacher', 'Title'),
            'original_filename' => Yii::t('substituteteacher', 'Original Filename'),
            'mime' => Yii::t('substituteteacher', 'Mime'),
            'size' => Yii::t('substituteteacher', 'Size'),
            'filename' => Yii::t('substituteteacher', 'Filename'),
            'created_at' => Yii::t('substituteteacher', 'Created At'),
            'updated_at' => Yii::t('substituteteacher', 'Updated At'),
            'deleted' => Yii::t('substituteteacher', 'Deleted'),
            'deleted_str' => Yii::t('substituteteacher', 'Deleted'),
            'uploadfile' => Yii::t('substituteteacher', 'Upload File'),
        ];
    }

    public function getSavepath()
    {
        $directory = Yii::getAlias('@upload');
        return $directory;
    }

    public function getFullFilepath()
    {
        $directory = $this->getSavepath();
        $file = $directory . DIRECTORY_SEPARATOR . $this->filename;
        return $file;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->deleted_str = $this->deleted == self::FILE_ACTIVE ? 'Όχι' : 'NAI';
    }

    /**
     * @inheritdoc
     * @return SubstituteTeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubstituteTeacherFileQuery(get_called_class());
    }
}
