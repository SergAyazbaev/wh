<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Class MailerForm
 * @package app\models
 */
class MailerForm extends Model
{
    public $fromEmail;
    public $fromName;
    public $toEmail;
    public $subject;
    public $body;

    /**
     * @return array
     */
    public function rules()
    {
        return [
//            [['fromEmail', 'fromName', 'toEmail', 'subject', 'body'], 'required' , 'message' => 'пустое поле' ],

            ['fromName',    'required', 'message' => 'от кого...' ],
            ['fromEmail',   'required', 'message' => 'с какой почты... ' ],
            ['toEmail',     'required', 'message' => 'на какую почту...' ],
            ['subject',     'required', 'message' => 'тема...' ],
            ['body',        'required', 'message' => 'само письмо...' ],

            ['fromEmail', 'email'],
            ['toEmail', 'email']
        ];
    }

    /**
     * @return bool
     */
    public function sendEmail()
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($this->toEmail)
                ->setFrom([$this->fromEmail => $this->fromName])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}