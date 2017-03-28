<?php

namespace DeviseBundle\Command;

use CommunBundle\Entity\Batch;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande permettant de récupérer le cours d'une devise
 * Class CoursDeviseCommand
 */
class CoursDeviseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cours:get')
            ->addOption('devise', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Devise à récupérer', null)
            ->addOption('date', null, InputOption::VALUE_OPTIONAL, 'Date à requêter', null)
            ->setDescription('Récupère le cours d\'une devise pour une journée');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln(array("Début de la commande de récupération des devises",
                               $now->format('d/m/Y à H:i:s'),
                               "***************"));

        // Protection contre une date
        $date = null;
        if (strlen($input->getOption('date')) > 0) {
            try {
                $date = new \DateTime($input->getOption('date'));
                $output->writeln('Date : ' . $date->format('d/m/Y H:i:S'));
            } catch (\Exception $e) {
                $date = null;
                $output->writeln('Date : null');
            }
        }

        $batch = $this->getContainer()->get('commun.batch')->lanceBatch(Batch::TYPE_IMPORT_DEVISE);
        $this->getContainer()->get('devise.devise')->recupereEtSauveCours($input->getOption('devise'), $date);
        $this->getContainer()->get('devise.devise')->updateCoursTouteDevise();
        $this->getContainer()->get('commun.batch')->arreteBatch($batch);

        $output->writeln('<comment>Fini !</comment>');
    }


}