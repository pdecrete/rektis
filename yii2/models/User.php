<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $last_login
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{

    const SCENARIO_UPDATE = 'update';
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $new_password;
    public $new_password_repeat;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
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
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['username', 'password_hash', 'password_reset_token', 'email', 'name', 'surname'], 'required'],
//            ['auth_key', 'required'],
            [['status'], 'integer'],
            [['username', 'email', 'name', 'surname'], 'string', 'max' => 128],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 200],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['new_password', 'new_password_repeat'], 'safe', 'on' => self::SCENARIO_UPDATE],
            ['new_password', 'string', 'min' => 6],
            ['new_password', 'compare', 'on' => self::SCENARIO_UPDATE],
            [['last_login', 'create_ts', 'update_ts'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Όνομα χρήστη',
            'auth_key' => 'Κλειδί επαλήθευσης',
            'password_hash' => 'Κρυπτογραφημένος κωδικός',
            'new_password' => 'Κωδικός πρόσβασης',
            'new_password_repeat' => 'Επανάληψη κωδικού πρόσβασης',
            'password_reset_token' => 'Τεκμήριο επαναφοράς κωδικού πρόσβασης',
            'email' => 'Email',
            'name' => 'Όνομα',
            'surname' => 'Επώνυμο',
            'status' => 'Κατάσταση',
            'last_login' => 'Τελευταία σύνδεση',
            'create_ts' => 'Χρονοσφραγίδα δημιουργίας',
            'update_ts' => 'Χρονοσφραγίδα ενημέρωσης',
        ];
    }

    public static function getStatusLabelsArray()
    {
        return [
            self::STATUS_ACTIVE => self::getLabelForStatus(User::STATUS_ACTIVE),
            self::STATUS_DELETED => self::getLabelForStatus(User::STATUS_DELETED)
        ];
    }

    public static function getLabelForStatus($status_code)
    {
        switch ($status_code) {
            case self::STATUS_DELETED:
                $status_label = 'Διεγραμμένος';
                break;
            case self::STATUS_ACTIVE:
                $status_label = 'Ενεργός';
                break;
            default:
                $status_label = 'ΑΓΝΩΣΤΗ ΚΑΤΑΣΤΑΣΗ';
                break;
        }
        return $status_label;
    }

    public function getStatuslabel()
    {
        return self::getLabelForStatus($this->status);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 
     * {@inheritdoc}
     * Also, if scenario is update, and password is set, update password.
     * 
     * @return boolean
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (($this->scenario === self::SCENARIO_UPDATE) && (strlen($this->new_password) > 0)) {
                $password_hash = Yii::$app->security->generatePasswordHash($this->new_password);
            }
            return true;
        } else {
            return false;
        }
    }

}
