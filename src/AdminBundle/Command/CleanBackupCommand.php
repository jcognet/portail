<?php

namespace AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande nettoyant le répertoire de backup
 * Class CleanBackupCommand
 */
class CleanBackupCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:backup:clean')
            ->setDescription('Nettoie les anciens backs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $backupService = $this->getContainer()->get('admin.back_up');
        $backupService->setOutput($output);
        $backupService->nettoieRepertoire();

    }


}