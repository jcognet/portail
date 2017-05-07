<?php

namespace CommunBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande permettant de remettre à 0 les données
 * Class CoursDeviseCommand
 */
class CleanProjetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('clean:projet')
            ->setDescription('Remet à 0 la database et charge les fixtures')
            ->addOption('duree', null, InputOption::VALUE_OPTIONAL, 'durée de requête (en jour) ', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Suppression de la base de données
        try {
            $commandDrop    = $this->getApplication()->find('doctrine:database:drop');
            $argumentsDrop  = array(
                '--force' => true,
            );
            $inputDrop      = new ArrayInput($argumentsDrop);
            $commandDrop->run($inputDrop, $output);
        }catch (\Exception $exception){

        }
        // Création
        $commandCreate    = $this->getApplication()->find('doctrine:database:create');
        $argumentsCreate  = array();
        $inputCreate      = new ArrayInput($argumentsCreate);
        $commandCreate->run($inputCreate, $output);
        // Création des tables
        $commandTablesCreate    = $this->getApplication()->find('doctrine:schema:update');
        $argumentsTablesCreate  = array(
            '--force' => true
        );
        $inputTablesCreate      = new ArrayInput($argumentsTablesCreate);
        $commandTablesCreate->run($inputTablesCreate, $output);
        // Execution de la commande des fixtures
        $commandFixtures    = $this->getApplication()->find('doctrine:fixtures:load');
        $argumentsFixtures  = array(
            '--no-interaction' => true
        );
        $inputFixtures      = new ArrayInput($argumentsFixtures);
        $commandFixtures->run($inputFixtures, $output);
        // récupération des devises
        $duree     = 60;
        if ($input->getOption('duree') > 0) {
            $duree = $input->getOption('duree');
        }
        $commandDevise    = $this->getApplication()->find('cours:get-periode');
        $argumentsDevise  = array(
            '--duree' => $duree,
        );
        $inputDevise      = new ArrayInput($argumentsDevise);
        $commandDevise->run($inputDevise, $output);
        // Ajout de quelques livres
        $listeIsbn = array('2955358401', '281041744X', '2373490641', '2302055853', '2800177195');
        foreach ($listeIsbn as $isbn) {
            $commandLivre   = $this->getApplication()->find('livre:google:get-by-isbn');
            $argumentsLivre = array(
                '--env' => 'test',
                'isbn'  => $isbn
            );
            $inputLivre     = new ArrayInput($argumentsLivre);
            $commandLivre->run($inputLivre, $output);
        }
    }


}