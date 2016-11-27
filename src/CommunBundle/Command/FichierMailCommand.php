<?php

namespace CommunBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande permettant d'envoyer un mail en pj
 * Class FichierMailCommand
 */
class FichierMailCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('mail:fichier')
            ->setDescription("Envoie un fichier ")
            ->addArgument('sujet', InputArgument::REQUIRED, "Sujet du mail")
            ->addArgument('fichier', InputArgument::REQUIRED, "Chemin du fichier à envoyer");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mailerTo = $this->getContainer()->getParameter('destinataire_mail');
        if (!is_null($mailerTo)) {
            $now     = new \DateTime();
            $message = \Swift_Message::newInstance($input->getArgument('sujet') . "(" . $now->format('d/m/Y H:i') . ")")
                ->setFrom($this->getContainer()->getParameter('mailer_from'))
                ->setTo($mailerTo)
                ->setBody(file_get_contents($input->getArgument('fichier')), 'text/html', 'utf-8');

            $this->getContainer()->get('mailer')->send($message);
        }


    }


}