<?php

namespace AppBundle\Email;

class EmailSender
{
    /**
     * @var \Swift_Mailer
     */
    protected  $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $emailFrom, string $emailTo, string $message, $renderedView)
    {

        $email = new \Swift_Message($message);

        $email->setFrom($emailFrom);
        $email->setTo($emailTo);
        $email->setBody(
                $renderedView,
            'text/html'
        );

        $this->mailer->send($email);

    }

}