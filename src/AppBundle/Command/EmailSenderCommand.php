<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class EmailSenderCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('reservation:send-email')
             ->setDescription('Send email to user.')
             ->setHelp('The command sends emails via crontab.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sender = $this->getContainer()->get('app.email.sender');

        $sender->send();

        $output->write('commandvagyok');
    }

}