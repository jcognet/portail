<?php

namespace BookBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande permettant de récupérer les informations d'un livre
 * Class CoursDeviseCommand
 */
class GoogleGetBookCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('book:google:get-by-isbn')
            ->setDescription('Fait appel à Google pour récupérer les informations d\'un livre.')
            ->addArgument('isbn', null, InputArgument::REQUIRED, 'ISBN de livre à chercher', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isbn = $input->getArgument('isbn');
        $now  = new \DateTime();
        $output->writeln(array("Début de la commande de récupération du livre",
                               $now->format('d/m/Y à H:i:s'),
                               'ISBN : ' . $isbn,
                               "***************"));
        $livreService = $this->getContainer()->get('livre.google_get_book_service');
        $livreService->setOutput($output);
        $res = $livreService->rechercheLivreParISBN($isbn);
        $output->writeln('Retour livre avec ID :  '.$res->getId().'-Titre : '.$res->getTitre() );
    }


}