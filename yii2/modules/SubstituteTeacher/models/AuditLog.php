<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\log\Logger;
use yii\web\User;

/**
 * This is the model class for table "{{%staudit_log}}".
 *
 * @property integer $id
 * @property integer $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 */
class AuditLog extends \yii\db\ActiveRecord
{
    public $level_label;
    public $user; // if set will hold the user object

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%staudit_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level'], 'integer'],
            [['log_time'], 'number'],
            [['prefix', 'message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'level' => Yii::t('substituteteacher', 'Level'),
            'category' => Yii::t('substituteteacher', 'Category'),
            'log_time' => Yii::t('substituteteacher', 'Log Time'),
            'prefix' => Yii::t('substituteteacher', 'Prefix'),
            'message' => Yii::t('substituteteacher', 'Message'),
            'user' => Yii::t('substituteteacher', 'User'),
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        switch ($this->level) {
            case Logger::LEVEL_ERROR:
                $this->level_label = '<span class="label label-danger">ERROR</span>';
                break;
            case Logger::LEVEL_INFO:
                $this->level_label = '<span class="label label-info">Info</span>';
                break;
            case Logger::LEVEL_TRACE:
                $this->level_label = '<span class="label label-default">Trace</span>';
                break;
            case Logger::LEVEL_WARNING:
                $this->level_label = '<span class="label label-warning">Warning</span>';
                break;
            default:
                $this->level_label = $this->level;
                break;
        }

        $prefix_parts = explode('][', $this->prefix, 3);
        $user = (isset($prefix_parts[1]) ? $prefix_parts[1] : '-');
        $user_identity_class = Yii::$app->user->identityClass;
        $this->user = $user_identity_class::findOne(['id' => $user]);
    }

    public static function filterOptions($for = 'level')
    {
        switch ($for) {
            case 'level': $options = [
                    Logger::LEVEL_ERROR => 'Error',
                    Logger::LEVEL_WARNING => 'Warning',
                    Logger::LEVEL_INFO => 'Info',
                    Logger::LEVEL_TRACE => 'Trace'
                ];
                break;
            default: $options = [];
                break;
        }
        return $options;
    }

    /**
     * @inheritdoc
     * @return AuditLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuditLogQuery(get_called_class());
    }
}
