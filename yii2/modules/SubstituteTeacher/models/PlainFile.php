<?php
namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\base\Model;

/**
 * 
 */
class PlainFile extends Model
{

    public $name;
    public $uploadfile;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'uploadfile'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Filename'),
            'uploadfile' => Yii::t('app', 'Upload File'),
        ];
    }

//    public function contact($email)
//    {
//        if ($this->validate()) {
//            $subj = "[admapp] {$this->subject}";
//
//            $sent = false;
//            try {
//                $sent = Yii::$app->mailer->compose()
//                    ->setTo($email)
//                    ->setFrom([$this->email => $this->name])
//                    ->setSubject($subj)
//                    ->setTextBody($this->body)
//                    ->setHtmlBody($this->body)
//                    ->send();
//            } catch (\Swift_TransportException $e) { // exception 'Swift_TransportException'
//                $logStr = 'Swift_TransportException in contact-email-sending: ' .
//                    $e->getMessage();
//                Yii::info($logStr, 'contact-email');
//                Yii::$app->session->setFlash('danger', Yii::t('app', 'Your request was not sent.'));
//            }
//            if (!Yii::$app->user->isGuest) {
//                $userName = Yii::$app->user->identity->username;
//            } else {
//                $userName = 'Guest';
//            }
//
//            if ($sent == true) {
//                $logStr = 'User [' . $userName . '] used ContactForm. From-email: [' . $this->email . ']. From-name: [' . $this->name . ']. Subject: [' . $subj . ']. Body: [' . $this->body . '].';
//                Yii::info($logStr, 'contact-email');
//                return true;
//            } else {
//                $logStr = 'User [' . $userName . '] tried to use ContactForm but the email was not sent. From-email: [' . $this->email . ']. From-name: [' . $this->name . ']. Subject: [' . $subj . ']. Body: [' . $this->body . '].';
//                Yii::info($logStr, 'contact-email');
//                return false;
//            }
//        }
//        return false;
//    }
}
