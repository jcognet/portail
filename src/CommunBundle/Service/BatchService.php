<?php

namespace CommunBundle\Service;

use CommunBundle\Entity\Batch;
use Doctrine\ORM\EntityManager;


/**
 * Class BatchService
 * @package CommunBundle\Service
 */
class BatchService
{
    use  OutputTrait;

    /**
     * * Entity manager
     * @var EntityManager|null
     */
    protected $em = null;


    /**
     * AlertService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * lance un batch
     * @param $type
     * @return Batch
     */
    public function lanceBatch($type)
    {
        $batch = new Batch();
        $batch->setFin(false);
        $batch->setDate(new \DateTime());
        $batch->setType($type);
        $this->em->persist($batch);
        $type->em->flush();
        return $batch;
    }

    /**
     * Ajouter un commentaire
     * @param Batch $batch
     * @param $commentaire
     * @return Batch
     */
    public function ajouteCommentaire(Batch $batch, $commentaire)
    {
        if (strlen($batch->getCommentaire()) > 0)
            $commentaire = $batch->getCommentaire() . "\n" . $commentaire;
        $batch->setCommentaire($commentaire);
        $this->em->persist($batch);
        $type->em->flush();
        return $batch;
    }

    /**
     * Arrête un batch
     * @param Batch $batch
     * @param string $commentaire
     */
    public function arreteBatch(Batch $batch, $commentaire = "")
    {
        if (strlen($commentaire) > 0) {
            $this->ajouteCommentaire($batch, $commentaire);
        }
    }

}

