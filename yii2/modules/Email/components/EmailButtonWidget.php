<?php 

namespace app\modules\Email\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class EmailButtonWidget extends Widget
{
    private $email_postman_route; // handler of the emails
    private $envelope; // data to send to the handler 

    public $label; 
    public $tooltip;
    public $redirect_route; // where to go after action is taken; string url or route options
    public $from; // who sends the email 
    public $to;
    public $cc;
    public $template; // page to use as template for email subject and body 
    public $template_data; // substitutions for template placeholders 
    public $files; // attachments
    public $enable_upload; // if true, allows for an uploaded file to be sent

    public function init()
    {
        parent::init();

        $this->email_postman_route = [
            '/Email/postman/send'
        ];

        if (empty($this->label)) {
            $this->label = 'ΑΠΟΣΤΟΛΗ!';
        }

        $this->envelope = [];        
        foreach (['from', 'to', 'cc', 'template', 'template_data', 'redirect_route', 'files'] as $field) {
            if (!empty($this->$field)) {
                $this->envelope[$field] = $this->$field;
            }
        }
    }
    public function run()
    {
        return $this->render('email-button', [
            'email_postman_route' => $this->email_postman_route,
            'envelope' => base64_encode(serialize($this->envelope)),
            'enable_upload' => boolval($this->enable_upload),
            'label' => $this->label,
            'tooltip' => $this->tooltip
        ]);
    }
}
