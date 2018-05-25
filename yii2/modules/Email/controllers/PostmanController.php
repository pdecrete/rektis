<?php

namespace app\modules\Email\controllers;

use Yii;
use app\modules\Pages\models\Page;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
        $sent = 0;

        try {
            $save_directory = Yii::$app->getModule('Email')->params['archive-repository'] . uniqid(date('YmdHis_'), true);
            if (false === mkdir($save_directory)) {
                throw new \Exception('Αδυναμία αρχικοποίησης αποθετηρίου αρχείων.');
            }

            $data = PostmanController::prepareData($data);

            \Yii::info($data, __METHOD__);

            $template = Page::find()->identity($data['template'])->one();
            if (empty($template)) {
                throw new NotFoundHttpException('Το πρότυπο αποστολής δεν υπάρχει.');
            }

            list($subject, $body) = PostmanController::parseTemplate($template, $data);

            $messages = PostmanController::prepareMessages($subject, $body, $data);

            $sent = PostmanController::realSendMessages($messages);

            PostmanController::archiveMessages($messages, $save_directory);
        } catch (NotFoundHttpException $e) {
            $msg = $e->__toString();
            \Yii::error(substr($msg, 0, strpos($msg, "\n")), __METHOD__);
            // let this one be known
            throw $e;
        } catch (\Exception $e) {
            $msg = $e->__toString();
            \Yii::error(substr($msg, 0, strpos($msg, "\n")), __METHOD__);
        }

        return $sent;
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
        try {
            $save_directory = $this->module->params['archive-repository'] . uniqid(date('YmdHis_'), true);
            if (false === mkdir($save_directory)) {
                Yii::$app->session->addFlash('danger', 'Αδυναμία αρχικοποίησης αποθετηρίου αρχείων.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $envelope = Yii::$app->request->post('envelope');
            if (empty($envelope)) {
                Yii::$app->session->addFlash('danger', 'Ανύπαρκτα στοιχεία για πραγματοποίηση της αποστολής.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $data = PostmanController::prepareData(unserialize(base64_decode($envelope, true)));

            // get any extra file; anly save those that were uploadedl all attachments will be saved in the eml (if archive is enabled)
            $attachment = UploadedFile::getInstanceByName('attachment');
            if (null !== $attachment) {
                $filename = $save_directory . DIRECTORY_SEPARATOR . $attachment->name;
                if (true !== $attachment->saveAs($filename)) {
                    @unlink($attachment->tempName);
                    Yii::$app->session->addFlash('danger', 'Αδυναμία αποθήκευσης του αρχείου.');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                $data['files'][] = $filename;
            }

            \Yii::info($data, __METHOD__);

            $template = Page::find()->identity($data['template'])->one();
            if (empty($template)) {
                Yii::$app->session->addFlash('danger', 'Δεν εντοπίστηκε το πρότυπο αποστολής.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            list($subject, $body) = PostmanController::parseTemplate($template, $data);

            $messages = PostmanController::prepareMessages($subject, $body, $data);

            $sent = PostmanController::realSendMessages($messages);
            if ($sent == count($messages)) {
                Yii::$app->session->addFlash('info', "Στάλθηκαν συνολικά {$sent} email. Ζητήθηκε αποστολή σε διευθύνσεις to=[" . implode(', ', $data['to']) . "], cc=[" . implode(', ', $data['cc']) . "], bcc=[" . implode(', ', $data['bcc']) . "]");
            } else {
                Yii::$app->session->addFlash('danger', "Δεν στάλθηκαν όλα τα email (στάλθηκαν συνολικά μόνο {$sent}). Ζητήθηκε αποστολή σε διευθύνσεις to=[" . implode(', ', $data['to']) . "], cc=[" . implode(', ', $data['cc']) . "], bcc=[" . implode(', ', $data['bcc']) . "]");
            }

            PostmanController::archiveMessages($messages, $save_directory);
        } catch (NotFoundHttpException $e) {
            $msg = $e->__toString();
            \Yii::error(substr($msg, 0, strpos($msg, "\n")), __METHOD__);
            // let this one be known
            throw $e;
        } catch (\Exception $e) {
            Yii::$app->session->addFlash('danger', 'Προέκυψε σφάλμα κατά τη διαδικασία αποστολής. Σχετικό μήνυμα λάθους: ' .$e->getMessage());
            $msg = $e->__toString();
            \Yii::error(substr($msg, 0, strpos($msg, "\n")), __METHOD__);
        }

        return $this->redirect($data['redirect_route']);
    }

    public static function prepareData($data)
    {
        $data = array_merge([
            'from' => Yii::$app->getModule('Email')->params['from'],
            'replyTo' => Yii::$app->getModule('Email')->params['reply-to'],
            'to' => '',
            'cc' => '',
            'template' => '',
            'template_data' => [],
            'files' => [],
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
        if (!empty($data['cc'])) {
            foreach ($data['cc'] as $email) {
                $message = Yii::$app->mailer->compose()
                    ->setFrom($data['from'])
                    ->setReplyTo($data['replyTo'])
                    ->setSubject($subject)
                    ->setHtmlBody($body)
                    ->setTo($email);
                $messages[] = $message;
            }
        }
        // send all bcc together 
        if (!empty($data['bcc'])) {
            $message = Yii::$app->mailer->compose()
                ->setFrom($data['from'])
                ->setReplyTo($data['replyTo'])
                ->setSubject($subject . ' [αντίγραφο]')
                ->setHtmlBody($body)
                ->setBcc($data['bcc']);
            $messages[] = $message;
        }
        array_walk($messages, function ($message) use ($data) {
            if (!empty($data['files'])) {
                foreach ($data['files'] as $file) {
                    $message->attach($file);
                }
            }
        });
        return $messages;
    }

    /**
     * Send emails; does no handle exception so as to handle in main worker method.
     */
    public static function realSendMessages($messages)
    {
        return Yii::$app->mailer->sendMultiple($messages);
    }

    /**
     * Save emails to archive repository; does no handle exception so as to handle in main worker method.
     */
    public static function archiveMessages($messages, $savedir)
    {
        if (true === Yii::$app->getModule('Email')->params['archive-messages']) {
            array_walk($messages, function ($message, $idx) use ($savedir) {
                file_put_contents("{$savedir}/{$idx}.eml", $message->toString());
            });
        }
    }
}
