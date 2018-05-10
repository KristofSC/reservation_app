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

    public function send(string $template, array $parameters = null)
    {

        $email = new \Swift_Message('Sikeres időpont foglalás!');

        $email->setFrom('scytha87@gmail.com');
        $email->setTo('scytha87@gmail.com');
        $email->setBody(
            $this->renderView(
                'AppBundle::successEmail.html.twig',
                [
                    'lastname' => $this->getUser()->getLastname(),
                    'firstName' => $this->getUser()->getFirstName()
                ]
            ),
            'text/html'
        );

    }

}