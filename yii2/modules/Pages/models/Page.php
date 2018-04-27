<?php

namespace app\modules\Pages\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $id
 * @property string $identity
 * @property string $title
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class Page extends ActiveRecord
{
    public $updated_at_str;
    public $created_at_str;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity', 'title'], 'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['identity', 'title'], 'string', 'max' => 255],
            [['identity'], 'unique'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identity' => 'Αναγνωριστικό λεκτικό',
            'title' => 'Τίτλος',
            'content' => 'Περιεχόμενο',
            'created_at' => 'Δημιουργήθηκε',
            'updated_at' => 'Ενημερώθηκε',
            'created_at_str' => 'Δημιουργήθηκε',
            'updated_at_str' => 'Ενημερώθηκε',
        ];
    }

    public function afterFind()
    {
        $this->created_at_str = date("d/m/Y H:i:s", $this->created_at);
        $this->updated_at_str = date("d/m/Y H:i:s", $this->updated_at);
    }

    /**
     *
     * @param string $identity
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public static function getPageContent($identity)
    {
        $model = static::find()->identity($identity)->one();
        if ($model) {
            return $model->content;
        } else {
            throw new \yii\web\NotFoundHttpException();
        }
    }

    /**
     * @inheritdoc
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }
}
