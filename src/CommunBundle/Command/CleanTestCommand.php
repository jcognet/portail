<?php

namespace CommunBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande permettant de remettre à 0 les données de test
 * Class CoursDeviseCommand
 */
class CleanTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('clean:test')
            ->setDescription('Remet à 0 la database et charge la base de données de test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->getContainer()->getParameter('kernel.environment') != 'test') {
            $output->writeln('Cette commande doit être seulement exécutée en environnement de test');
            die();
        }
        try {
            // Suppression de la base de données
            $commandDrop    = $this->getApplication()->find('doctrine:database:drop');
            $argumentsDrop  = array(
                '--force' => true,
                '--env'   => 'test'
            );
            $inputDrop      = new ArrayInput($argumentsDrop);
            $returnCodeDrop = $commandDrop->run($inputDrop, $output);
        }catch (\Exception $e){

        }
        // Création
        $commandCreate    = $this->getApplication()->find('doctrine:database:create');
        $argumentsCreate  = array(
            '--env' => 'test',
        );
        $inputCreate      = new ArrayInput($argumentsCreate);
        $returnCodeCreate = $commandCreate->run($inputCreate, $output);
        // Lecture du dump de test
        $dbUser     = $this->getContainer()->getParameter('database_user');
        $dbHost     = $this->getContainer()->getParameter('database_host');
        $dbName     = $this->getContainer()->getParameter('database_name');
        $dbPassword = $this->getContainer()->getParameter('database_password');
        $command    = "mysql -h " . $dbHost . " -u " . $dbUser . " ";
        if (strlen($dbPassword) > 0) {
            $command .= "-p" . $dbPassword . " ";
        }
        $command .= $dbName . " < test/SQL/data.sql";
        //echo $command;
        // Exécution
        $outputCommand = array();
        $outputCode    = array();
        $output->writeln('Lancement de la commande');
        exec($command, $outputCommand, $outputCode);
    }


}