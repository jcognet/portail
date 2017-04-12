<?php

namespace CommunBundle\Repository;

use CommunBundle\Entity\News;

/**
 * NewsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Récupère une liste de news triée par date de mise en ligne
     * @param $maxNews
     * @return array
     */
    public function getListe($maxNews)
    {
        return $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.dateMiseEnLigne<:date')
            ->setParameter('date', new \DateTime())
            ->addOrderBy('n.dateMiseEnLigne', 'DESC')
            ->setMaxResults($maxNews)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère la dernière news
     * @return mixed
     */
    public function getDerniere()
    {
        return $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.dateMiseEnLigne<:date')
            ->setParameter('date', new \DateTime())
            ->addOrderBy('n.dateMiseEnLigne', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * Retourne la news suivante
     * @param News|null $news
     * @return mixed|null
     */
    public function getSuivante(News $news = null)
    {
        if (true === is_null($news))
            return null;

        return $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.dateMiseEnLigne>:date and n.dateMiseEnLigne<:now')
            ->setParameter('date', $news->getDateMiseEnLigne())
            ->setParameter('now', new \DateTime())
            ->addOrderBy('n.dateMiseEnLigne', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne la news précédente
     * @param News|null $news
     * @return mixed|null
     */
    public function getPrecedente(News $news = null)
    {
        if (true === is_null($news))
            return null;

        return $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.dateMiseEnLigne<:date')
            ->setParameter('date', $news->getDateMiseEnLigne())
            ->addOrderBy('n.dateMiseEnLigne', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * Liste toutes les news
     * @param $inTouteActu Si true affiche toutes les news
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryListe($inTouteActu = false)
    {
        $builder = $this->createQueryBuilder('n');
        if (false === $inTouteActu)
            $builder->andWhere('n.dateMiseEnLigne<= :now')
                ->setParameter('now', new \DateTime());

        $builder->addOrderBy('n.dateMiseEnLigne', 'DESC')
            ->getQuery();
        return $builder;
    }


    /**
     * Récupère la liste des news dans le futur
     * @return array
     */
    public function getListeNewsFutur()
    {
        return  $this->createQueryBuilder('n')
            ->andWhere('n.dateMiseEnLigne> :now')
            ->setParameter('now', new \DateTime())
            ->addOrderBy('n.dateMiseEnLigne', 'DESC')
            ->getQuery()
            ->getResult()
        ;

    }
}
