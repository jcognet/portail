<?php

namespace BookBundle\Repository;

use BookBundle\Entity\Auteur;
use BookBundle\Entity\BaseLivre;
use BookBundle\Entity\Categorie;
use BookBundle\Entity\Synonyme;
use BookBundle\Traits\SynonymeTrait;

/**
 * SynonymeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SynonymeRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Met à jour un synonyme
     * @param $objet
     * @param $label
     * @return Synonyme|\Doctrine\ORM\QueryBuilder
     * @throws \Exception
     */
    public function persist($objet, $label)
    {
        // Vérifie l'entité
        if (false === $this->verifieClass($objet)) {
            throw new \Exception("Impossible de sauver les synonymes d'une entité de type " . get_class($objet) . " avec ID : " . $objet->getId());
        }
        // Vérification de l'unicité du label
        $synonyme = $this->findSynonyme($objet, $label);
        if (true === is_null($synonyme)) {
            // Création du synonyme
            $synonyme = new Synonyme();
            $synonyme->setMot($label)
                ->setType(get_class($objet))
                ->setObjetId($objet->getId());
            // Enregistrement du synonyme
            $this->getEntityManager()->persist($synonyme);
            $objet->addSynonyme($synonyme);
        }
        return $synonyme;
    }

    /**
     * Recherche un synonyme d'après un label passé en paramètre pour un objet donné
     * @param $objet
     * @param $label
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \Exception
     */
    public function findSynonyme($objet, $label)
    {
        // Vérifie l'entité
        if (false === $this->verifieClass($objet)) {
            throw new \Exception("Impossible de récupérer le synonyme " . $label . " d'une entité de type " . get_class($objet) . " avec ID : " . $objet->getId());
        }
        // Récupération du synonyme lié au bon type d'objet avec le label envoyé en paramètre
        return $this->createQueryBuilder('s')
            ->where('s.objetId = :objetId and s.type=:type and s.mot = :label')
            ->setParameter('objetId', $objet->getId())
            ->setParameter('label', $label)
            ->setParameter('type', get_class($objet))
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Cherche si le synonyme avec le label passé en paramètre existe pour un objet donné
     * @param $objet
     * @param $label
     * @return bool
     */
    public function hasSynonyme($objet, $label)
    {
        return is_null($this->findSynonyme($objet, $label));
    }

    /**
     * Récupère tous les synonymes d'un objets
     * @param $objet
     * @return array
     * @throws \Exception
     */
    public function findTousSynonyme($objet)
    {
        // Vérifie l'entité
        if (false === $this->verifieClass($objet)) {
            throw new \Exception("Impossible de récupérer les synonymes d'une entité de type " . get_class($objet) . " avec ID : " . $objet->getId());
        }
        // Récupération de tous les synonymes liés au bon type d'objet avec le label envoyé en paramètre
        return $this->createQueryBuilder('s')
            ->where('s.objetId = :objetId and s.type=:type ')
            ->setParameter('objetId', $objet->getId())
            ->setParameter('type', get_class($objet))
            ->getQuery()
            ->getResult();
    }

    public function findObjetBySynonyme($class, $label)
    {
        $synonyme = $this->createQueryBuilder('s')
            ->where('s.type = :type and s.mot=:mot')
            ->setParameter('type', $class)
            ->setParameter('mot', $label)

            ->getQuery()
            ->getOneOrNullResult();
        $outObjet = null;
        if (false === is_null($synonyme)) {
            $outObjet = $this->getEntityManager()->getRepository('BookBundle:'.$this->getRepositoryFromClassName($class))->find($synonyme->getId());
        }
        return $outObjet;
    }

    /**
     * Vérifie si l'entité gère bien les synnomes
     * @param $objet
     * @return bool
     */
    protected function verifieClass($objet)
    {
        return in_array(SynonymeTrait::class, class_uses(get_class($objet)));
    }

    /**
     * Récupère le nom d'un repository à partir de sa classe
     * @param $class
     * @return string
     */
    protected function getRepositoryFromClassName($class){
        return substr($class, strrpos($class, '\\')+1);
    }
}
