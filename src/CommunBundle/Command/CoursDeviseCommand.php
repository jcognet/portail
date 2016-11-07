<?php

namespace CommunBundle\Command;

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
    /**
     * Nom du fichier de log à envoyer par mail
     * @var string
     */
    protected $fileName = "";

    protected function configure()
    {
        $this
            ->setName('cours:get')
            ->addOption('devise', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Devise à récupérer', null)
            ->addOption('date', null, InputOption::VALUE_OPTIONAL, 'Date à requêter', null)
            ->setDescription('Récupère la devise d\'une commande');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Protection contre une date
        $date = null;
        if (strlen($input->getOption('date')) > 0) {
            try {
                $date = new \DateTime($input->getOption('date'));
            } catch (\Exception $e) {
                $date = null;
            }
        }
        $this->getContainer()->get('commun.devise')->recupereEtSauveCours($input->getOption('devise'), $date);
    }


}