<?php

namespace DeviseBundle\Command;

use TransverseBundle\Entity\Batch;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
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
        $now = new \DateTime();
        $output->writeln(array("Début de la commande d'alerte",
                               $now->format('d/m/Y à H:i:s'),
                               "***************"));

        $batch  = $this->getContainer()->get('transverse.batch')->lanceBatch(Batch::TYPE_ALERTE_ENVOYEE);
        $alerteService = $this->getContainer()->get('devise.alert');
        $alerteService->setOutput($output);
        $erreur = $alerteService->previentUtilisateurs();
        // Si aucune erreur => chaine de caractère vide
        if (count($erreur) == 0) {
            $erreur = "";
        }
        $this->getContainer()->get('transverse.batch')->arreteBatch($batch, $erreur);

        $output->writeln('<comment>Fini !</comment>');
    }


}