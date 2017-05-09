<?php

namespace LivreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande permettant d'ajouter un synonyme à un livre
 * Class CoursDeviseCommand
 */
class AddSynonymeToLivreCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('livre:add:synonyme')
            ->setDescription('Ajouter un synonyme à un libre.')
            ->addArgument('type', null, InputArgument::REQUIRED, "Type d'objet à traiter")
            ->addArgument('livreId', null, InputArgument::REQUIRED, 'Id du livre à traiter')
            ->addArgument('synonyme', null, InputArgument::REQUIRED, 'Synonyme à ajouter');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Traitement des arguments
        $livreId       = $input->getArgument('livreId');
        $synonymeLabel = $input->getArgument('synonyme');
        $type          = $input->getArgument('type');
        // Affichage du début du travail
        $now = new \DateTime();
        $output->writeln(array("Début de la commande d'ajouter un synonyme",
                               $now->format('d/m/Y à H:i:s'),
                               'ID du livre : ' . $livreId,
                               'Synonyme à ajouter : ' . $synonymeLabel,
                               "***************"));
        $em         = $this->getContainer()->get('doctrine.orm.entity_manager');
        $entityType = null;
        switch (strtolower($type)) {
            case "auteur":
                $entityType = "Auteur";
                break;
            case "livre":
                $entityType = "BaseLivre";
                break;
            case "categorie":
                $entityType = "Categorie";
                break;
            case "editeur":
                $entityType = "Editeur";
                break;
            default:
                throw new \Exception("Type inconnu : " . $type);
        }
        // Récupération de l'objet
        $objet = $em->getRepository('LivreBundle:' . $entityType)->find($livreId);
        if (true === is_null($livreId)) {
            throw new \Exception('Livre inconnu : ' . $livreId);
        }
        // Ajout du synonyme
        $synonyme = $em->getRepository('LivreBundle:Synonyme')->persist($objet, $synonymeLabel);
        $output->writeln('Retour du persist ' . $synonyme->getMot() . " avec ID : " . $synonyme->getId());
        $em->flush();
        // Récupération de tous les synonymes
        $listeSynonymes = $em->getRepository('LivreBundle:Synonyme')->findTousSynonyme($objet);
        $output->writeln('Liste des synonymes');
        foreach ($listeSynonymes as $s)
            $output->writeln($s->getMot() . " avec ID : " . $s->getId());
        // Recherche d'un objet à partir de son synonyme
    }


}