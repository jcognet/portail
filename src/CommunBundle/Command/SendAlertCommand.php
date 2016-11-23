<?php

namespace CommunBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande permettant d'envoyer les alertes aux utilisateurs
 * Class SendAlertCommand
 */
class SendAlertCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('alert:send')
            ->setDescription('Envoie les alertes aux utilisateurs qui en ont besoin');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alerteService = $this->getContainer()->get('commun.alert');
        $alerteService->setOutput($output);
        $alerteService->previentUtilisateurs();
    }


}