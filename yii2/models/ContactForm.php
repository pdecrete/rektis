<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => Yii::t('app', 'Verification Code'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'subject' => Yii::t('app', 'Subject'),
            'body' => Yii::t('app', 'Body'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            $subj = "[admapp] {$this->subject}";

            $sent = false;
            try {
                $sent = Yii::$app->mailer->compose()
                    ->setTo($email)
                    ->setFrom([$this->email => $this->name])
                    ->setSubject($subj)
                    ->setTextBody($this->body)
                    ->setHtmlBody($this->body)
                    ->send();
            } catch (\Swift_TransportException $e) { // exception 'Swift_TransportException'
                $logStr = 'Swift_TransportException in contact-email-sending: ' .
                    $e->getMessage();
                Yii::info($logStr, 'contact-email');
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Your request was not sent.'));
            }
            if (!Yii::$app->user->isGuest) {
                $userName = Yii::$app->user->identity->username;
            } else {
                $userName = 'Guest';
            }

            if ($sent == true) {
                $logStr = 'User [' . $userName . '] used ContactForm. From-email: [' . $this->email . ']. From-name: [' . $this->name . ']. Subject: [' . $subj . ']. Body: [' . $this->body . '].';
                Yii::info($logStr, 'contact-email');
                return true;
            } else {
                $logStr = 'User [' . $userName . '] tried to use ContactForm but the email was not sent. From-email: [' . $this->email . ']. From-name: [' . $this->name . ']. Subject: [' . $subj . ']. Body: [' . $this->body . '].';
                Yii::info($logStr, 'contact-email');
                return false;
            }
        }
        return false;
    }
}
