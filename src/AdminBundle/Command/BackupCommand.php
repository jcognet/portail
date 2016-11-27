<?php

namespace AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Commande permettant de remettre à 0 les données
 * Class BackupCommand
 */
class BackupCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:backup')
            ->setDescription('Enregistre la base de données (et supprime les anciennes bases)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Paramèetre BDD
        $dbUser     = $this->getContainer()->getParameter('database_user');
        $dbHost     = $this->getContainer()->getParameter('database_host');
        $dbName     = $this->getContainer()->getParameter('database_name');
        $dbPassword = $this->getContainer()->getParameter('database_password');
        // Nom du fichier SQL
        $now      = new \DateTime();
        $fileName = $now->format('Y_m_d_H_i_s') . "_" . $dbName . ".sql";
        $output->writeln('Nom du fichier SQL : ' . $fileName);
        // Nom du répertoire de backup
        $repertoire = $this->getContainer()->getParameter('kernel.root_dir') . $this->getContainer()->getParameter('backup_directory');
        $fs         = new Filesystem();
        if (!$fs->exists($repertoire)) {
            $fs->mkdir($repertoire);
        }
        $repertoire = realpath($repertoire);
        if (substr($repertoire, -1) != '/' && substr($repertoire, -1) != '\\') {
            $repertoire .= DIRECTORY_SEPARATOR;
        }
        $output->writeln("Répertoire d'enregistrement : " . $repertoire);
        // création de la commande
        //TODO : gérer le port
        $command = "mysqldump -h " . $dbHost . " -u " . $dbUser . " ";
        if (strlen($dbPassword) > 0) {
            $command .= "-p " . $dbPassword . " ";
        }
        $command .= $dbName . " > " . $repertoire . $fileName;
        //echo $command;
        // Exécution
        $outputCommand = array();
        $outputCode    = array();
        $output->writeln('Lancement de la commande');
        exec($command, $outputCommand, $outputCode);
        if (is_array($outputCommand)) {
            $output->writeln('Résultat :', implode("\n", $outputCommand));
        } else {
            $output->writeln('Résultat :' . $outputCommand);
        }
        // Nettoyage
        $finder = new Finder();
        foreach ($finder->files()->in($repertoire)->name("*.sql")->date('>' . $this->getContainer()->getParameter('backup_duration') . 'days') as $file) {
            $fs->remove($file);
        }


    }


}