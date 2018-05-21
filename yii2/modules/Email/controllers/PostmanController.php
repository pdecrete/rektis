<?php

namespace app\modules\Email\controllers;

use Yii;
use app\modules\Pages\models\Page;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\base\View;

/**
 *
 */
class PostmanController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'send' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['send'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Convinience method to send an email directly by code.
     * TODO: move this code elsewhere more appropriately
     *
     * @return int the number of successfully sent email; 0 if none
     */
    public static function send($data)
    {
        $data = PostmanController::prepareData($data);

        $template = Page::find()->identity($data['template'])->one();
        if (empty($template)) {
            throw new NotFoundHttpException('Το πρότυπο αποστολής δεν υπάρχει.');
        }

        list($subject, $body) = PostmanController::parseTemplate($template, $data);

        $messages = PostmanController::prepareMessages($subject, $body, $data);

        return PostmanController::realSendMessages($messages);
    }

    /**
     * Sends an email.
     * Expects various parameters via a unified base64 encoded string named 'envelope'.
     * @see EmailButtonWidget for example constructing the required parameter
     * @see send() for another description
     *
     * @return int the number of successfully sent email; 0 if none
     */
    public function actionSend()
    {
        $envelope = Yii::$app->request->post('envelope');
        if (empty($envelope)) {
            Yii::$app->session->addFlash('danger', 'Ανύπαρκτα στοιχεία για πραγματοποίηση της αποστολής.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $data = PostmanController::prepareData(unserialize(base64_decode($envelope, true)));

        $template = Page::find()->identity($data['template'])->one();
        if (empty($template)) {
            Yii::$app->session->addFlash('danger', 'Δεν εντοπίστηκε το πρότυπο αποστολής.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        list($subject, $body) = PostmanController::parseTemplate($template, $data);

        $messages = PostmanController::prepareMessages($subject, $body, $data);

        $sent = PostmanController::realSendMessages($messages);
        Yii::$app->session->addFlash('info', "Στάλθηκαν συνολικά {$sent} email. Ζητήθηκε αποστολή σε διευθύνσεις to=[" . implode(', ', $data['to']) . "], cc=[" . implode(', ', $data['cc']) . "], bcc=[" . implode(', ', $data['bcc']) . "]" );
        
        return $this->redirect($data['redirect_route']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public static function prepareData($data)
    {
        $data = array_merge([
            'from' => Yii::$app->getModule('Email')->params['from'],
            'replyTo' => Yii::$app->getModule('Email')->params['replyTo'],
            // 'replyTo' => $this->module->params['replyTo'],
            'to' => '',
            'cc' => '',
            'template' => '',
            'template_data' => [],
            'files' => '',
            'redirect_route' => Yii::$app->request->referrer // default redirect
        ], $data);
        //unify to,cc fields
        if (!empty($data['to']) && !is_array($data['to'])) {
            $data['to'] = [ $data['to'] ];
        }
        if (!empty($data['cc']) && !is_array($data['cc'])) {
            $data['cc'] = [ $data['cc'] ];
        }
        $data['bcc'] = Yii::$app->getModule('Email')->params['shadow-recipients'];

        // validate files for attachments
        if (!empty($data['files'])) {
            foreach ($data['files'] as $file) {
                if (!is_readable($file)) {
                    throw new NotFoundHttpException('Το αρχείο ' . basename($file) . ' δεν βρέθηκε.');
                }
            }
        }

        return $data;
    }

    public static function parseTemplate($template, $data)
    {
        if (isset($data['template_data'])) {
            $subject = strtr($template->title, $data['template_data']);
            $body = strtr($template->content, $data['template_data']);
        } else {
            $subject = $template->title;
            $body = $template->content;
        }

        $body .= Yii::$app->view->render('@app/modules/Email/views/postman/_email-footer');

        return [$subject, $body];
    }

    public static function prepareMessages($subject, $body, $data)
    {
        $messages = [];

        if (!empty($data['to'])) {
            foreach ($data['to'] as $email) {
                $message = Yii::$app->mailer->compose()
                    ->setFrom($data['from'])
                    ->setReplyTo($data['replyTo'])
                    ->setSubject($subject)
                    ->setHtmlBody($body)
                    ->setTo($email);
                $messages[] = $message;
            }
        }
        // if (!empty($data['cc'])) {
        //     foreach ($data['cc'] as $email) {
        //         $message = Yii::$app->mailer->compose()
        //             ->setFrom($data['from'])
        //             ->setReplyTo($data['replyTo'])
        //             ->setSubject($subject)
        //             ->setHtmlBody($body)
        //             ->setTo($email);
        //         $messages[] = $message;
        //     }
        // }
        // if (!empty($data['bcc'])) {
        //     foreach ($data['bcc'] as $email) {
        //         $message = Yii::$app->mailer->compose()
        //             ->setFrom($data['from'])
        //             ->setReplyTo($data['replyTo'])
        //             ->setSubject($subject . ' [αντίγραφο]')
        //             ->setHtmlBody($body)
        //             ->setTo($email);
        //         $messages[] = $message;
        //     }
        // }
        array_walk($messages, function ($message) use ($data) {
            if (!empty($data['files'])) {
                foreach ($data['files'] as $file) {
                    $message->attach($file);
                }
            }    
        });
        return $messages;
    }

    public static function realSendMessages($messages)
    {
        $sent = 0;
        try {
            $sent = Yii::$app->mailer->sendMultiple($messages);
        } catch (\Swift_TransportException $e) {
            $logStr = 'Swift_TransportException sending: ';
            $pos = strpos($e, 'Stack trace:');
            if ($pos>0) {
                $logStr .= substr($e, 0, $pos);
            }
            Yii::info($logStr, 'leave-email');
        }
        return $sent;
    }
}
